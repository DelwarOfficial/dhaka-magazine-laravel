<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Post;
use Database\Seeders\CategorySeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class FillDemoCategoryPosts extends Command
{
    protected $signature = 'demo:fill-category-posts
        {--min=10 : Minimum direct posts per category/subcategory}
        {--dry-run : Show the report without creating posts}';

    protected $description = 'Create compact Bangla demo posts so every category and subcategory has enough UI test content.';

    private array $authors = [
        'ঢাকা ম্যাগাজিন ডেস্ক',
        'নিজস্ব প্রতিবেদক',
        'স্টাফ রিপোর্টার',
        'বিশেষ প্রতিনিধি',
        'অনলাইন ডেস্ক',
    ];

    public function handle(): int
    {
        if (! Schema::hasTable('posts') || ! Schema::hasTable('categories')) {
            $this->error('posts and categories tables must exist before generating demo posts.');

            return self::FAILURE;
        }

        app(CategorySeeder::class)->run();

        $minimum = max(1, (int) $this->option('min'));
        $dryRun = (bool) $this->option('dry-run');
        $images = $this->prepareDemoImages();
        $report = [];

        Category::query()
            ->with('parent')
            ->orderByRaw('COALESCE(parent_id, id)')
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->get()
            ->each(function (Category $category) use ($minimum, $dryRun, $images, &$report) {
                $existing = $this->directPostCount($category);
                $needed = max(0, $minimum - $existing);

                for ($i = 1; $i <= $needed; $i++) {
                    if (! $dryRun) {
                        $this->createDemoPost($category, $existing + $i, $images);
                    }
                }

                $report[] = [
                    'name' => $category->name_bn ?? $category->name,
                    'slug' => $category->slug,
                    'parent' => $category->parent?->slug ?? 'Parent',
                    'existing' => $existing,
                    'created' => $needed,
                    'final' => $existing + $needed,
                    'status' => ($existing + $needed) >= $minimum ? 'OK' : 'MISSING',
                ];
            });

        $this->exportReport($report);
        $this->table(
            ['Category', 'Slug', 'Parent', 'Existing', 'Created', 'Final', 'Status'],
            collect($report)->map(fn (array $row) => [
                $row['name'],
                $row['slug'],
                $row['parent'],
                $row['existing'],
                $row['created'],
                $row['final'],
                $row['status'],
            ])->all()
        );

        $created = collect($report)->sum('created');
        $missing = collect($report)->where('status', 'MISSING')->count();

        $this->info(($dryRun ? 'Dry run complete.' : 'Demo post generation complete.'));
        $this->line("New posts created: {$created}");
        $this->line("Categories still missing posts: {$missing}");
        $this->line('Report: ' . storage_path('app/reports/demo-category-fill-report.csv'));

        return self::SUCCESS;
    }

    private function directPostCount(Category $category): int
    {
        if ($category->parent_id) {
            return Post::query()->where('subcategory_id', $category->id)->count();
        }

        return Post::query()
            ->where('category_id', $category->id)
            ->whereNull('subcategory_id')
            ->count();
    }

    private function createDemoPost(Category $category, int $index, array $images): void
    {
        $parent = $category->parent;
        $isChild = (bool) $parent;
        $categoryName = $category->name_bn ?? $category->name;
        $parentName = $parent?->name_bn ?? $categoryName;
        $title = $this->titleFor($categoryName, $index);
        $excerpt = "{$categoryName} বিষয়ে সর্বশেষ তথ্য, প্রেক্ষাপট ও নাগরিক জীবনের প্রভাব নিয়ে সংক্ষিপ্ত প্রতিবেদন।";
        $body = implode("\n\n", [
            "{$categoryName} নিয়ে নতুন এই প্রতিবেদনে সংশ্লিষ্ট খাতের সাম্প্রতিক পরিস্থিতি তুলে ধরা হয়েছে। স্থানীয় সূত্র, বিশেষজ্ঞ মতামত ও চলমান বাস্তবতার ভিত্তিতে বিষয়টি পাঠকের জন্য সহজভাবে সাজানো হয়েছে।",
            "ঢাকা ম্যাগাজিনের এই ডেমো কনটেন্টটি UI পরীক্ষা, কার্ড লেআউট, ক্যাটাগরি পেজ এবং সংবাদ তালিকার ভারসাম্য যাচাই করার জন্য তৈরি। বাস্তব প্রকাশনার আগে সম্পাদকীয় যাচাই প্রয়োজন।",
            "{$parentName} বিভাগের পাঠকদের জন্য খবরটি সংক্ষিপ্ত, তথ্যভিত্তিক ও মোবাইল-ফ্রেন্ডলি উপস্থাপনায় রাখা হয়েছে।",
        ]);
        $slug = $this->uniqueSlug('demo-' . $category->slug . '-' . Str::slug(Str::limit($title, 38, '')) . '-' . $index);
        $image = $images[($index - 1) % count($images)] ?? null;

        Post::query()->create([
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'body' => $body,
            'featured_image' => $image,
            'source_url' => null,
            'source_name' => $this->authors[$index % count($this->authors)],
            'published_at' => now()->subMinutes(($category->id * 13) + $index),
            'category_id' => $isChild ? $parent->id : $category->id,
            'subcategory_id' => $isChild ? $category->id : null,
            'category_slug' => $isChild ? $parent->slug : $category->slug,
            'subcategory_slug' => $isChild ? $category->slug : null,
            'meta_title' => "{$title} | Dhaka Magazine",
            'meta_description' => Str::limit($excerpt . ' পড়ুন Dhaka Magazine-এ।', 155, ''),
            'status' => 'published',
            'needs_editorial_review' => false,
            'raw_import_payload' => [
                'demo' => true,
                'category' => $categoryName,
                'parent_category' => $parentName,
            ],
        ]);
    }

    private function titleFor(string $categoryName, int $index): string
    {
        $subjects = [
            'নতুন উদ্যোগে মানুষের আগ্রহ বাড়ছে',
            'সাম্প্রতিক পরিস্থিতি নিয়ে বিশেষ আলোচনা',
            'পরিবর্তনের প্রভাব পড়ছে জনজীবনে',
            'বিশেষজ্ঞরা দেখছেন নতুন সম্ভাবনা',
            'নিরাপদ ও কার্যকর ব্যবস্থাপনায় জোর',
            'সেবার মান উন্নয়নে নেওয়া হচ্ছে পদক্ষেপ',
            'স্থানীয় পর্যায়ে বাড়ছে সচেতনতা',
            'নতুন পরিকল্পনায় গুরুত্ব পাচ্ছে নাগরিক সুবিধা',
            'সাম্প্রতিক সিদ্ধান্তে এসেছে ইতিবাচক সাড়া',
            'আগামী দিনের চ্যালেঞ্জ নিয়ে প্রস্তুতি',
        ];

        return "{$categoryName}: " . $subjects[($index - 1) % count($subjects)];
    }

    private function uniqueSlug(string $base): string
    {
        $base = trim($base, '-') ?: 'demo-news';
        $slug = $base;
        $attempt = 2;

        while (Post::query()->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$attempt}";
            $attempt++;
        }

        return $slug;
    }

    private function prepareDemoImages(): array
    {
        $targetDir = storage_path('app/public/demo-news');
        File::ensureDirectoryExists($targetDir);

        return collect(range(1, 8))
            ->map(function (int $number) use ($targetDir) {
                $source = public_path("images/news-{$number}.jpg");
                $target = "{$targetDir}/news-{$number}.jpg";

                if (File::exists($source) && ! File::exists($target)) {
                    File::copy($source, $target);
                }

                return File::exists($target) ? "demo-news/news-{$number}.jpg" : null;
            })
            ->filter()
            ->values()
            ->all();
    }

    private function exportReport(array $report): void
    {
        $path = storage_path('app/reports/demo-category-fill-report.csv');
        File::ensureDirectoryExists(dirname($path));

        $handle = fopen($path, 'w');
        fwrite($handle, "\xEF\xBB\xBF");
        fputcsv($handle, ['Category name', 'Slug', 'Parent category', 'Existing posts', 'Newly created', 'Final total', 'Status']);

        foreach ($report as $row) {
            fputcsv($handle, [
                $row['name'],
                $row['slug'],
                $row['parent'],
                $row['existing'],
                $row['created'],
                $row['final'],
                $row['status'],
            ]);
        }

        fclose($handle);
    }
}
