<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Support\ArticleFeed;
use App\Support\FallbackDataService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MapHomepageSections extends Command
{
    protected $signature = 'homepage:map-sections {--force : Rebuild homepage section flags from the legacy visible layout}';

    protected $description = 'Assign the currently visible homepage posts to Breaking, Featured, Sticky, Trending, and Editor pick flags.';

    public function handle(): int
    {
        if (! Schema::hasTable('posts') || ! $this->columnsReady()) {
            $this->error('The posts table or homepage section columns are missing. Run php artisan migrate first.');

            return self::FAILURE;
        }

        $fallbackArticles = FallbackDataService::getArticles();
        $articles = ArticleFeed::homepageArticles($fallbackArticles);

        $sections = [
            'breaking' => [
                'flag' => 'is_breaking_news',
                'order' => 'breaking_news_order',
                'limit' => 10,
                'ids' => $this->postIdsForSlugs([
                    'metro-rail-new-route',
                    'cricket-world-cup-win',
                    'ai-new-development',
                    'economic-growth-report',
                    'new-hospital-dhaka',
                    'international-climate-summit',
                    'new-movie-release',
                    'student-protest-update',
                    'tech-startup-funding',
                    'agricultural-innovation',
                ]),
            ],
            'featured' => [
                'flag' => 'is_featured',
                'order' => 'featured_order',
                'limit' => 1,
                'ids' => $this->articleIdsAt($articles, [0]),
            ],
            'sticky' => [
                'flag' => 'is_sticky',
                'order' => 'sticky_order',
                'limit' => 6,
                'ids' => $this->articleIdsAt($articles, [1, 2, 6, 7, 8, 3]),
            ],
            'trending' => [
                'flag' => 'is_trending',
                'order' => 'trending_order',
                'limit' => 5,
                'ids' => $this->articleIdsAt($articles, [4, 7, 10, 16, 19]),
            ],
            'editors_pick' => [
                'flag' => 'is_editors_pick',
                'order' => 'editors_pick_order',
                'limit' => 3,
                'ids' => $this->articleIdsAt($articles, [5, 9, 3]),
            ],
        ];

        $claimedIds = [];
        $sectionNames = array_keys($sections);
        $originalSectionIds = collect($sections)
            ->map(fn(array $section) => $section['ids'])
            ->all();

        foreach ($sectionNames as $index => $name) {
            $futureReservedIds = collect(array_slice($sectionNames, $index + 1))
                ->flatMap(fn(string $futureName) => $originalSectionIds[$futureName] ?? [])
                ->filter(fn($id) => filled($id))
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values()
                ->all();

            // Preserve the legacy visible posts first, then add the next available
            // homepage posts when duplicate prevention would otherwise leave a
            // later widget visually short.
            $sections[$name]['ids'] = $this->padSectionIds(
                $sections[$name]['ids'],
                $articles,
                $sections[$name]['limit'],
                $claimedIds,
                $futureReservedIds
            );

            $claimedIds = array_values(array_unique(array_merge($claimedIds, $sections[$name]['ids'])));
        }

        DB::transaction(function () use ($sections) {
            foreach ($sections as $name => $section) {
                if (! $this->option('force') && Post::query()->where($section['flag'], true)->exists()) {
                    $this->line("Skipped {$name}; it already has mapped posts.");

                    continue;
                }

                if ($this->option('force')) {
                    Post::query()->update([
                        $section['flag'] => false,
                        $section['order'] => null,
                    ]);
                }

                foreach (array_values(array_unique($section['ids'])) as $index => $id) {
                    Post::query()
                        ->whereKey($id)
                        ->update([
                            $section['flag'] => true,
                            $section['order'] => $index + 1,
                        ]);
                }

                $this->info("Mapped {$name}: " . count(array_unique($section['ids'])) . ' post(s).');
            }
        });

        // Homepage data can be stored by the database cache driver in this project.
        // Clearing these caches after remapping prevents stale ticker or Blade output.
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        $this->info('Homepage section mapping complete. Application and view caches were cleared.');

        return self::SUCCESS;
    }

    private function columnsReady(): bool
    {
        foreach ([
            'is_breaking_news',
            'breaking_news_order',
            'is_featured',
            'featured_order',
            'is_sticky',
            'sticky_order',
            'is_trending',
            'trending_order',
            'is_editors_pick',
            'editors_pick_order',
        ] as $column) {
            if (! Schema::hasColumn('posts', $column)) {
                return false;
            }
        }

        return true;
    }

    private function articleIdsAt(array $articles, array $indexes): array
    {
        return collect($indexes)
            ->map(fn(int $index) => $articles[$index]['id'] ?? null)
            ->filter(fn($id) => filled($id))
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();
    }

    private function postIdsForSlugs(array $slugs): array
    {
        $posts = Post::query()
            ->whereIn('slug', $slugs)
            ->get(['id', 'slug'])
            ->keyBy('slug');

        return collect($slugs)
            ->map(fn(string $slug) => $posts[$slug]->id ?? null)
            ->filter(fn($id) => filled($id))
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();
    }

    private function padSectionIds(array $ids, array $articles, int $limit, array $claimedIds, array $futureReservedIds): array
    {
        $selected = collect($ids)
            ->filter(fn($id) => filled($id))
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        $visibleSelectedCount = $selected
            ->reject(fn(int $id) => in_array($id, $claimedIds, true))
            ->count();

        if ($visibleSelectedCount >= $limit) {
            return $selected->all();
        }

        $padding = collect($articles)
            ->pluck('id')
            ->filter(fn($id) => filled($id))
            ->map(fn($id) => (int) $id)
            ->reject(fn(int $id) => in_array($id, $claimedIds, true)
                || in_array($id, $futureReservedIds, true)
                || $selected->contains($id))
            ->take($limit - $visibleSelectedCount);

        return $selected
            ->concat($padding)
            ->values()
            ->all();
    }
}
