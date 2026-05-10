<?php

namespace App\Support;

use App\Helpers\DateHelper;
use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ArticleFeed
{
    private const SECTION_COLUMNS = [
        'breaking' => ['flag' => 'is_breaking_news', 'order' => 'breaking_news_order', 'scope' => 'breakingNews'],
        'featured' => ['flag' => 'is_featured', 'order' => 'featured_order', 'scope' => 'featured'],
        'sticky' => ['flag' => 'is_sticky', 'order' => 'sticky_order', 'scope' => 'sticky'],
        'trending' => ['flag' => 'is_trending', 'order' => 'trending_order', 'scope' => 'trending'],
        'editors_pick' => ['flag' => 'is_editors_pick', 'order' => 'editors_pick_order', 'scope' => 'editorsPick'],
    ];

    public static function homepageArticles(array $fallbackArticles, int $limit = 40): array
    {
        $posts = self::publicPosts()
            ->take($limit)
            ->map(fn(Post $post) => self::toArticleArray($post));

        if ($posts->isNotEmpty()) {
            return $posts->values()->all();
        }

        return collect($fallbackArticles)->take($limit)->values()->all();
    }

    public static function breakingNews(array $fallbackArticles, int $limit = 10, array $exceptIds = []): array
    {
        return self::homepageSection('breaking', $fallbackArticles, $limit, $exceptIds);
    }

    public static function featured(array $fallbackArticles, int $limit = 1, array $exceptIds = []): array
    {
        return self::homepageSection('featured', $fallbackArticles, $limit, $exceptIds);
    }

    public static function sticky(array $fallbackArticles, int $limit = 6, array $exceptIds = []): array
    {
        return self::homepageSection('sticky', $fallbackArticles, $limit, $exceptIds);
    }

    public static function trending(array $fallbackArticles, int $limit = 5, array $exceptIds = []): array
    {
        return self::homepageSection('trending', $fallbackArticles, $limit, $exceptIds);
    }

    public static function editorsPick(array $fallbackArticles, int $limit = 3, array $exceptIds = []): array
    {
        return self::homepageSection('editors_pick', $fallbackArticles, $limit, $exceptIds);
    }

    public static function localNews(array $fallbackArticles, int $limit = 9): array
    {
        $posts = self::localNewsPosts($limit)
            ->map(fn(Post $post) => self::toArticleArray($post))
            ->unique('id')
            ->values();

        if ($posts->isNotEmpty()) {
            return $posts->all();
        }

        if (self::localNewsColumnsReady()) {
            // Once the ID columns exist, an empty Local News query should stay empty.
            // The mapping command is responsible for preserving legacy visible posts
            // by assigning complete division/district/upazila IDs.
            return [];
        }

        // Pre-migration fallback only: keep the old visual content available on
        // fresh checkouts until the location-ID migration and mapper have run.
        return self::legacyLocalNewsSection(self::homepageArticles($fallbackArticles))
            ->take($limit)
            ->values()
            ->all();
    }

    public static function categoryArticles(array $categorySlugs, array $fallbackArticles, int $limit = 30, ?string $division = null, ?string $district = null, ?string $upazila = null): array
    {
        $posts = self::categoryPosts($categorySlugs, $limit, $division, $district, $upazila)
            ->map(fn(Post $post) => self::toArticleArray($post));

        if (self::hasLocationFilter($division, $district, $upazila)) {
            return $posts->values()->all();
        }

        return $posts
            ->concat(collect($fallbackArticles)->whereIn('category_slug', $categorySlugs))
            ->values()
            ->all();
    }

    public static function findArticle(string $slug, array $fallbackArticles): ?array
    {
        if (!self::postsTableReady()) {
            return collect($fallbackArticles)->firstWhere('slug', $slug);
        }

        try {
            $post = Post::query()
                ->whereIn('status', self::publicStatuses())
                ->where('slug', $slug)
                ->first();
        } catch (\Throwable) {
            $post = null;
        }

        if ($post) {
            return self::toArticleArray($post, true);
        }

        return collect($fallbackArticles)->firstWhere('slug', $slug);
    }

    public static function allForRelated(array $fallbackArticles, int $limit = 80): array
    {
        return self::homepageArticles($fallbackArticles, $limit);
    }

    private static function publicPosts(): Collection
    {
        if (!self::postsTableReady()) {
            return collect();
        }

        try {
            return Post::query()
                ->with(['author', 'category.parent', 'subcategory.parent'])
                ->whereIn('status', self::publicStatuses())
                ->latest('published_at')
                ->latest('id')
                ->take(100)
                ->get();
        } catch (\Throwable) {
            return collect();
        }
    }

    private static function homepageSection(string $section, array $fallbackArticles, int $limit, array $exceptIds = []): array
    {
        $posts = self::sectionPosts($section, $limit, $exceptIds)
            ->map(fn(Post $post) => self::toArticleArray($post));

        if ($posts->isNotEmpty()) {
            return $posts->values()->all();
        }

        // Fallback is deliberately scoped to the legacy slice that used to feed this UI
        // section. It keeps fresh installs and unmigrated databases visually stable while
        // still preventing duplicate IDs once earlier homepage sections have claimed them.
        return collect(self::legacyHomepageSection($section, self::homepageArticles($fallbackArticles), $fallbackArticles))
            ->reject(fn(array $article) => self::articleIdExcluded($article, $exceptIds))
            ->take($limit)
            ->values()
            ->all();
    }

    private static function sectionPosts(string $section, int $limit, array $exceptIds = []): Collection
    {
        if (! self::homepageSectionColumnsReady($section)) {
            return collect();
        }

        try {
            $meta = self::SECTION_COLUMNS[$section];
            $scope = $meta['scope'];

            return Post::query()
                ->with(['author', 'category.parent', 'subcategory.parent'])
                ->published()
                ->{$scope}()
                ->when($exceptIds !== [], function (Builder $query) use ($exceptIds) {
                    // Runtime de-duping keeps a post from appearing in multiple homepage
                    // regions when an editor intentionally or accidentally enables more
                    // than one section flag on the same post.
                    $query->whereNotIn('id', array_values(array_unique($exceptIds)));
                })
                ->orderByRaw("CASE WHEN {$meta['order']} IS NULL THEN 1 ELSE 0 END")
                ->orderBy($meta['order'])
                ->latest('published_at')
                ->latest('id')
                ->take($limit)
                ->get();
        } catch (\Throwable) {
            return collect();
        }
    }

    private static function categoryPosts(array $categorySlugs, int $limit, ?string $division = null, ?string $district = null, ?string $upazila = null): Collection
    {
        if (!self::postsTableReady()) {
            return collect();
        }

        try {
            $hasLocationFilter = self::hasLocationFilter($division, $district, $upazila);

            if ($hasLocationFilter && ! self::locationColumnsReady()) {
                return collect();
            }

            $query = Post::query()
                ->with(['author', 'category.parent', 'subcategory.parent'])
                ->whereIn('status', self::publicStatuses())
                ->where(function ($query) use ($categorySlugs) {
                    $query
                        ->whereIn('category_slug', $categorySlugs)
                        ->orWhereIn('subcategory_slug', $categorySlugs)
                        ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->whereIn('slug', $categorySlugs))
                        ->orWhereHas('subcategory', fn ($categoryQuery) => $categoryQuery->whereIn('slug', $categorySlugs));
                });
                
            if ($division) {
                $query->where('division', $division);
            }

            if ($district) {
                $query->where('district', $district);
            }

            if ($upazila) {
                $query->where('upazila', $upazila);
            }

            return $query->latest('published_at')
                ->latest('id')
                ->take($limit)
                ->get();
        } catch (\Throwable) {
            return collect();
        }
    }

    private static function localNewsPosts(int $limit): Collection
    {
        if (! self::localNewsColumnsReady()) {
            return collect();
        }

        try {
            return Post::query()
                ->with([
                    'author',
                    'category.parent',
                    'subcategory.parent',
                    'divisionLocation',
                    'districtLocation',
                    'upazilaLocation',
                ])
                ->published()
                // Local News is location-based, not just category-based: a post
                // must have all three CMS location selections saved as IDs.
                ->whereNotNull('division_id')
                ->whereNotNull('district_id')
                ->whereNotNull('upazila_id')
                ->whereHas('districtLocation', function (Builder $query) {
                    $query->whereColumn('districts.division_id', 'posts.division_id');
                })
                ->whereHas('upazilaLocation', function (Builder $query) {
                    $query->whereColumn('upazilas.division_id', 'posts.division_id')
                        ->whereColumn('upazilas.district_id', 'posts.district_id');
                })
                ->orderByRaw('COALESCE(published_at, created_at) DESC')
                ->latest('id')
                ->take($limit)
                ->get();
        } catch (\Throwable) {
            return collect();
        }
    }

    private static function postsTableReady(): bool
    {
        try {
            return class_exists(Post::class) && Schema::hasTable('posts');
        } catch (\Throwable) {
            return false;
        }
    }

    private static function homepageSectionColumnsReady(string $section): bool
    {
        try {
            if (! self::postsTableReady() || ! isset(self::SECTION_COLUMNS[$section])) {
                return false;
            }

            return Schema::hasColumn('posts', self::SECTION_COLUMNS[$section]['flag'])
                && Schema::hasColumn('posts', self::SECTION_COLUMNS[$section]['order']);
        } catch (\Throwable) {
            return false;
        }
    }

    private static function locationColumnsReady(): bool
    {
        try {
            return Schema::hasColumn('posts', 'division')
                && Schema::hasColumn('posts', 'district')
                && Schema::hasColumn('posts', 'upazila');
        } catch (\Throwable) {
            return false;
        }
    }

    private static function localNewsColumnsReady(): bool
    {
        try {
            return self::postsTableReady()
                && Schema::hasColumn('posts', 'division_id')
                && Schema::hasColumn('posts', 'district_id')
                && Schema::hasColumn('posts', 'upazila_id')
                && Schema::hasTable('districts')
                && Schema::hasTable('upazilas');
        } catch (\Throwable) {
            return false;
        }
    }

    private static function hasLocationFilter(?string $division, ?string $district, ?string $upazila): bool
    {
        return filled($division) || filled($district) || filled($upazila);
    }

    private static function publicStatuses(): array
    {
        return ['published'];
    }

    private static function legacyHomepageSection(string $section, array $articles, array $fallbackArticles): array
    {
        return match ($section) {
            'breaking' => self::legacyBreakingArticles($articles, $fallbackArticles),
            'featured' => array_slice($articles, 0, 1),
            'sticky' => self::articlesAt($articles, [1, 2, 6, 7, 8, 3]),
            'trending' => self::articlesAt($articles, [4, 7, 10, 16, 19]),
            'editors_pick' => self::articlesAt($articles, [5, 9, 3]),
            default => [],
        };
    }

    private static function legacyBreakingArticles(array $articles, array $fallbackArticles): array
    {
        $tickerSlugs = [
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
        ];

        return collect($tickerSlugs)
            ->map(fn(string $slug) => collect($articles)->firstWhere('slug', $slug)
                ?: collect($fallbackArticles)->firstWhere('slug', $slug))
            ->filter()
            ->values()
            ->all();
    }

    private static function articlesAt(array $articles, array $indexes): array
    {
        return collect($indexes)
            ->map(fn(int $index) => $articles[$index] ?? null)
            ->filter()
            ->values()
            ->all();
    }

    private static function legacyLocalNewsSection(array $articles): Collection
    {
        return collect([18, 10, 6, 4, 15, 19, 8, 2, 11])
            ->map(fn(int $index) => $articles[$index] ?? null)
            ->filter()
            ->unique('id')
            ->values();
    }

    private static function articleIdExcluded(array $article, array $exceptIds): bool
    {
        return isset($article['id']) && in_array($article['id'], $exceptIds, true);
    }

    private static function toArticleArray(Post $post, bool $includeBody = false): array
    {
        $category = PostCategoryResolver::categoryFor($post);
        $categoryRoute = PostCategoryResolver::categoryRoute($category);

        $publishedAt = $post->published_at ?: $post->created_at;

        $article = [
            'id' => $post->id,
            'slug' => $post->slug,
            'title' => $post->title,
            'category' => $category['name_bn'] ?? PostCategoryResolver::fallbackCategory()['name_bn'],
            'category_slug' => $category['slug'] ?? PostCategoryResolver::FALLBACK_SLUG,
            'category_url' => $categoryRoute,
            'excerpt' => $post->excerpt ?: Str::limit(strip_tags((string) ($post->content ?: $post->body)), 170),
            'author' => $post->author?->name ?: $post->source_name ?: 'ঢাকা ম্যাগাজিন ডেস্ক',
            'date' => DateHelper::getBengaliDate($publishedAt),
            'time_ago' => DateHelper::timeAgo($publishedAt),
            'image_url' => self::postImageUrl($post),
            'views' => (int) ($post->view_count ?? 0),
            'tags' => [],
        ];

        if ($includeBody) {
            $article['body'] = collect(preg_split('/\R{2,}/', (string) ($post->content ?: $post->body)))
                ->map(fn(string $paragraph) => trim($paragraph))
                ->filter()
                ->values()
                ->all();
            $article['location'] = null;
            $article['meta_title'] = $post->meta_title ?: $post->title;
            $article['meta_description'] = $post->meta_description ?: Str::limit(strip_tags((string) $post->excerpt), 155);
        }

        return $article;
    }

    private static function imageUrl(?string $path): string
    {
        if (! $path) {
            return asset('images/news-1.jpg');
        }

        if (Str::startsWith($path, ['http://', 'https://', '//', '/images/', 'images/'])) {
            return Str::startsWith($path, ['http://', 'https://', '//'])
                ? $path
                : asset(ltrim($path, '/'));
        }

        if (! Str::contains($path, '/') && file_exists(public_path("images/{$path}"))) {
            return asset("images/{$path}");
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    private static function postImageUrl(Post $post): string
    {
        if ($post->image_path) {
            $filename = basename($post->image_path);

            return file_exists(public_path("images/{$filename}"))
                ? asset("images/{$filename}")
                : self::placeholderImageUrl();
        }

        return self::imageUrl($post->featured_image);
    }

    private static function placeholderImageUrl(): string
    {
        foreach (['placeholder.jpg', 'news-1.jpg', 'coming-soon-ad.webp'] as $filename) {
            if (file_exists(public_path("images/{$filename}"))) {
                return asset("images/{$filename}");
            }
        }

        return asset('images/news-1.jpg');
    }
}
