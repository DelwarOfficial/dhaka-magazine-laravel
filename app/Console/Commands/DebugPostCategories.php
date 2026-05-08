<?php

namespace App\Console\Commands;

use App\Http\Controllers\HomeController;
use App\Models\Post;
use Database\Seeders\CategorySeeder;
use App\Support\CategoryRepository;
use App\Support\PostCategoryResolver;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class DebugPostCategories extends Command
{
    protected $signature = 'categories:debug-posts
        {--dry-run : Generate the report without updating posts}
        {--no-sync-latest : Do not sync fallback latest-page articles into posts}';

    protected $description = 'Audit post category slugs, fix safe matches, and export a category post report.';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $this->showSchemaSummary();

        if (! Schema::hasTable('posts')) {
            $this->error('The posts table was not found.');
            $this->exportCategoryReport([]);

            return self::FAILURE;
        }

        $this->syncCategoriesTable();
        $synced = $this->syncLatestFallbackArticles($dryRun);
        $stats = [
            'total' => Post::query()->count(),
            'valid' => 0,
            'missing' => 0,
            'invalid' => 0,
            'fixed' => 0,
            'review' => 0,
            'categorized_final' => 0,
        ];
        $categoryCounts = [];

        Post::query()->orderBy('id')->chunkById(100, function ($posts) use (&$stats, &$categoryCounts, $dryRun) {
            foreach ($posts as $post) {
                $originalStatus = $this->originalStatus($post);
                $stats[$originalStatus]++;

                // Normalize child categories into category_slug + subcategory_slug without creating duplicates.
                $assignment = PostCategoryResolver::assignmentFor($post);
                $this->countCategory($categoryCounts, $assignment);
                $stats['categorized_final']++;

                if ($assignment['needs_review']) {
                    $stats['review']++;
                    Log::warning('Post category needs manual review', [
                        'post_id' => $post->id,
                        'title' => $post->title,
                        'category_slug' => $post->category_slug,
                        'subcategory_slug' => $post->subcategory_slug,
                    ]);
                }

                if ($this->needsUpdate($post, $assignment)) {
                    $stats['fixed']++;

                    if (! $dryRun) {
                        $this->updatePost($post, $assignment);
                    }
                }
            }
        });

        $this->exportCategoryReport($categoryCounts);
        $this->printSummary($stats, $dryRun, $synced);

        return self::SUCCESS;
    }

    private function syncLatestFallbackArticles(bool $dryRun): int
    {
        if ((bool) $this->option('no-sync-latest')) {
            return 0;
        }

        $synced = 0;

        foreach (app(HomeController::class)->fallbackArticles() as $index => $article) {
            $slug = trim((string) ($article['slug'] ?? ''));

            if ($slug === '' || Post::query()->where('slug', $slug)->exists()) {
                continue;
            }

            $post = new Post([
                'title' => $article['title'] ?? $slug,
                'slug' => $slug,
                'excerpt' => $article['excerpt'] ?? null,
                'body' => $article['excerpt'] ?? $article['title'] ?? null,
                'featured_image' => $this->normalizeImagePath($article['image_url'] ?? null),
                'source_url' => null,
                'source_name' => $article['author'] ?? 'Dhaka Magazine Desk',
                'published_at' => now()->subMinutes($index * 10),
                'status' => 'published',
                'needs_editorial_review' => false,
                'raw_import_payload' => $article,
            ]);

            $assignment = PostCategoryResolver::assignmentFor($post);
            $post->category_slug = $assignment['category_slug'];
            $post->subcategory_slug = $assignment['subcategory_slug'];

            if (Schema::hasColumn('posts', 'category_id')) {
                $post->category_id = $assignment['category_id'];
            }

            if (Schema::hasColumn('posts', 'subcategory_id')) {
                $post->subcategory_id = $assignment['subcategory_id'];
            }

            $post->needs_editorial_review = $assignment['needs_review'];

            $synced++;

            if (! $dryRun) {
                $post->save();
            }
        }

        return $synced;
    }

    private function showSchemaSummary(): void
    {
        $this->line('Database relationship inspection:');
        $this->line('- posts table: ' . (Schema::hasTable('posts') ? 'FOUND' : 'MISSING'));
        $this->line('- categories table: ' . (Schema::hasTable('categories') ? 'FOUND' : 'MISSING'));

        if (Schema::hasTable('posts')) {
            $postCategoryColumns = collect(Schema::getColumnListing('posts'))
                ->filter(fn ($column) => str_contains($column, 'category'))
                ->values();

            $this->line('- posts category columns: ' . ($postCategoryColumns->isNotEmpty() ? $postCategoryColumns->implode(', ') : 'none found'));
        }

        $pivotTables = $this->tableNames()
            ->filter(fn ($table) => str_contains((string) $table, 'category') && str_contains((string) $table, 'post'))
            ->values();

        $this->line('- category/post pivot tables: ' . ($pivotTables->isNotEmpty() ? $pivotTables->implode(', ') : 'none found'));
        $this->newLine();
    }

    private function tableNames()
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            return collect(DB::select("SELECT name FROM sqlite_master WHERE type='table'"))->pluck('name');
        }

        if ($driver === 'mysql') {
            return collect(DB::select('SHOW TABLES'))->map(fn ($row) => array_values((array) $row)[0] ?? null)->filter();
        }

        return collect(Schema::getTables())->pluck('name');
    }

    private function originalStatus(Post $post): string
    {
        $categorySlug = trim((string) $post->category_slug);
        $subcategorySlug = trim((string) $post->subcategory_slug);

        if ($categorySlug === '' && $subcategorySlug === '') {
            return 'missing';
        }

        if (PostCategoryResolver::isValidSlug($subcategorySlug) || PostCategoryResolver::isValidSlug($categorySlug)) {
            return 'valid';
        }

        if ($this->validCategoryId($post->subcategory_id ?? null) || $this->validCategoryId($post->category_id ?? null)) {
            return 'valid';
        }

        return 'invalid';
    }

    private function needsUpdate(Post $post, array $assignment): bool
    {
        return $post->category_slug !== $assignment['category_slug']
            || $post->subcategory_slug !== $assignment['subcategory_slug']
            || (Schema::hasColumn('posts', 'category_id') && $post->category_id !== $assignment['category_id'])
            || (Schema::hasColumn('posts', 'subcategory_id') && $post->subcategory_id !== $assignment['subcategory_id'])
            || ($assignment['needs_review'] && Schema::hasColumn('posts', 'needs_editorial_review') && ! $post->needs_editorial_review);
    }

    private function updatePost(Post $post, array $assignment): void
    {
        $updates = [];

        if (Schema::hasColumn('posts', 'category_slug')) {
            $updates['category_slug'] = $assignment['category_slug'];
        }

        if (Schema::hasColumn('posts', 'subcategory_slug')) {
            $updates['subcategory_slug'] = $assignment['subcategory_slug'];
        }

        if (Schema::hasColumn('posts', 'category_id')) {
            $updates['category_id'] = $assignment['category_id'];
        }

        if (Schema::hasColumn('posts', 'subcategory_id')) {
            $updates['subcategory_id'] = $assignment['subcategory_id'];
        }

        if ($assignment['needs_review'] && Schema::hasColumn('posts', 'needs_editorial_review')) {
            $updates['needs_editorial_review'] = true;
        }

        if ($updates !== []) {
            $post->forceFill($updates)->save();
        }
    }

    private function countCategory(array &$categoryCounts, array $assignment): void
    {
        $categorySlug = $assignment['category_slug'];
        $subcategorySlug = $assignment['subcategory_slug'];

        $categoryCounts[$categorySlug] = ($categoryCounts[$categorySlug] ?? 0) + 1;

        if ($subcategorySlug) {
            $categoryCounts[$subcategorySlug] = ($categoryCounts[$subcategorySlug] ?? 0) + 1;
        }
    }

    private function exportCategoryReport(array $categoryCounts): void
    {
        $path = storage_path('app/reports/category-debug-report.csv');
        File::ensureDirectoryExists(dirname($path));

        // This file is intentionally simple so editors can open it directly in Excel/Sheets.
        $handle = fopen($path, 'w');
        fwrite($handle, "\xEF\xBB\xBF");
        fputcsv($handle, ['Category name', 'Slug', 'Parent category', 'Total posts', 'Status']);

        foreach (CategoryRepository::parents() as $parent) {
            $this->writeCategoryRow($handle, $parent, null, $categoryCounts);

            foreach ($parent['children'] as $child) {
                $this->writeCategoryRow($handle, $child, $parent['slug'], $categoryCounts);
            }
        }

        fclose($handle);
        File::copy($path, storage_path('app/reports/category-post-report.csv'));
        $this->info("CSV report exported: {$path}");
    }

    private function writeCategoryRow($handle, array $category, ?string $parentSlug, array $categoryCounts): void
    {
        $total = $categoryCounts[$category['slug']] ?? 0;

        fputcsv($handle, [
            $category['name_bn'] ?? $category['name_en'] ?? $category['slug'],
            $category['slug'],
            $parentSlug ?: 'Parent',
            $total,
            $total > 0 ? 'OK' : 'ZERO POSTS',
        ]);

        $this->line(sprintf(
            '%s | %s | %s | %d posts | %s',
            $category['name_bn'] ?? $category['slug'],
            $category['slug'],
            $parentSlug ?: 'Parent',
            $total,
            $total > 0 ? 'OK' : 'ZERO POSTS'
        ));
    }

    private function printSummary(array $stats, bool $dryRun, int $synced): void
    {
        $this->newLine();
        $this->info('Post category debug summary' . ($dryRun ? ' (dry run)' : ''));
        $this->line("Total posts: {$stats['total']}");
        $this->line("Categorized posts: {$stats['categorized_final']}");
        $this->line('Uncategorized posts: 0');
        $this->line("Originally valid posts: {$stats['valid']}");
        $this->line("Originally missing/invalid posts: " . ($stats['missing'] + $stats['invalid']));
        $this->line("Posts fixed automatically: {$stats['fixed']}");
        $this->line("Latest-page fallback posts synced: {$synced}");
        $this->line("Posts needing manual review: {$stats['review']}");
    }

    private function syncCategoriesTable(): void
    {
        if (! Schema::hasTable('categories')) {
            return;
        }

        app(CategorySeeder::class)->run();
    }

    private function validCategoryId($id): bool
    {
        return $id && Schema::hasTable('categories') && \App\Models\Category::query()->whereKey($id)->exists();
    }

    private function normalizeImagePath(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $path = (string) parse_url($path, PHP_URL_PATH) ?: $path;

        return ltrim($path, '/');
    }
}
