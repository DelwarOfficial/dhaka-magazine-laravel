<?php

namespace App\Support;

use App\Helpers\DateHelper;
use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ArticleFeed
{
    /**
     * Request-level caches prevent homepage widgets, fallbacks, and sidebar
     * services from repeating the same hydrated post queries during one render.
     */
    private static array $publicPostCache = [];
    private static array $locationIdCache = [];

    private const SECTION_COLUMNS = [
        'breaking' => ['flag' => 'is_breaking_news', 'order' => 'breaking_news_order', 'scope' => 'breakingNews'],
        'featured' => ['flag' => 'is_featured', 'order' => 'featured_order', 'scope' => 'featured'],
        'sticky' => ['flag' => 'is_sticky', 'order' => 'sticky_order', 'scope' => 'sticky'],
        'trending' => ['flag' => 'is_trending', 'order' => 'trending_order', 'scope' => 'trending'],
        'editors_pick' => ['flag' => 'is_editors_pick', 'order' => 'editors_pick_order', 'scope' => 'editorsPick'],
    ];

    public static function homepageArticles(?array $fallbackArticles = null, int $limit = 40): array
    {
        $fallbackArticles ??= FallbackDataService::getArticles();

        $posts = self::publicPosts($limit)
            ->take($limit)
            ->map(fn(Post $post) => self::toArticleArray($post));

        if ($posts->isNotEmpty()) {
            return $posts->values()->all();
        }

        return collect($fallbackArticles)->take($limit)->values()->all();
    }

    public static function breakingNews(?array $fallbackArticles = null, int $limit = 10, array $exceptIds = []): array
    {
        return self::homepageSection('breaking', $fallbackArticles, $limit, $exceptIds);
    }

    public static function featured(?array $fallbackArticles = null, int $limit = 1, array $exceptIds = []): array
    {
        return self::homepageSection('featured', $fallbackArticles, $limit, $exceptIds);
    }

    public static function sticky(?array $fallbackArticles = null, int $limit = 6, array $exceptIds = []): array
    {
        return self::homepageSection('sticky', $fallbackArticles, $limit, $exceptIds);
    }

    public static function trending(?array $fallbackArticles = null, int $limit = 5, array $exceptIds = []): array
    {
        return self::homepageSection('trending', $fallbackArticles, $limit, $exceptIds);
    }

    public static function editorsPick(?array $fallbackArticles = null, int $limit = 3, array $exceptIds = []): array
    {
        return self::homepageSection('editors_pick', $fallbackArticles, $limit, $exceptIds);
    }

    public static function localNews(?array $fallbackArticles = null, int $limit = 9): array
    {
        $fallbackArticles ??= FallbackDataService::getArticles();

        $posts = self::localNewsPosts($limit)
            ->map(fn(Post $post) => self::toArticleArray($post))
            ->unique('id')
            ->values();

        if ($posts->isNotEmpty()) {
            return $posts->all();
        }

        if (SchemaReadyCheck::hasLocalNewsColumns()) {
            return [];
        }

        return self::legacyLocalNewsSection(self::homepageArticles($fallbackArticles))
            ->take($limit)
            ->values()
            ->all();
    }

    public static function categoryArticles(array $categorySlugs, ?array $fallbackArticles = null, int $limit = 30, ?string $division = null, ?string $district = null, ?string $upazila = null): array
    {
        $fallbackArticles ??= FallbackDataService::getArticles();

        $posts = self::categoryPosts($categorySlugs, $limit, $division, $district, $upazila)
            ->map(fn(Post $post) => self::toArticleArray($post));

        if (self::hasLocationFilter($division, $district, $upazila)) {
            return $posts->values()->all();
        }

        if ($posts->isNotEmpty()) {
            return $posts->values()->all();
        }

        return collect($fallbackArticles)
            ->whereIn('category_slug', $categorySlugs)
            ->take($limit)
            ->values()
            ->all();
    }

    public static function categoryRelationshipArticles(array $categorySlugs, int $limit = 30, ?string $division = null, ?string $district = null, ?string $upazila = null): array
    {
        return self::categoryPosts($categorySlugs, $limit, $division, $district, $upazila, true)
            ->map(fn(Post $post) => self::toArticleArray($post))
            ->values()
            ->all();
    }

    public static function findArticle(string $slug, ?array $fallbackArticles = null): ?array
    {
        $fallbackArticles ??= FallbackDataService::getArticles();

        if (!SchemaReadyCheck::isPostsTableReady()) {
            return collect($fallbackArticles)->firstWhere('slug', $slug);
        }

        try {
            /** @var \App\Models\Post|null $post */
            $post = Post::query()
                ->withContentRelations()
                ->with(['divisionLocation', 'districtLocation', 'upazilaLocation'])
                ->whereIn('status', self::publicStatuses())
                ->where('slug', $slug)
                ->first();
        } catch (\Exception $e) {
            Log::error("Failed to find article [{$slug}]: " . $e->getMessage());
            $post = null;
        }

        if ($post) {
            return self::toArticleArray($post, true);
        }

        return collect($fallbackArticles)->firstWhere('slug', $slug);
    }

    public static function allForRelated(?array $fallbackArticles = null, int $limit = 80): array
    {
        return self::homepageArticles($fallbackArticles, $limit);
    }

    public static function postToArticleArray(Post $post, bool $includeBody = false): array
    {
        return self::toArticleArray($post, $includeBody);
    }

    private static function publicPosts(int $limit = 40): Collection
    {
        if (!SchemaReadyCheck::isPostsTableReady()) {
            return collect();
        }

        $limit = max(1, $limit);
        $cacheKey = "latest:{$limit}";

        if (array_key_exists($cacheKey, self::$publicPostCache)) {
            return self::$publicPostCache[$cacheKey];
        }

        try {
            return self::$publicPostCache[$cacheKey] = Post::query()
                ->withContentRelations()
                ->whereIn('status', self::publicStatuses())
                ->latest('published_at')
                ->latest('id')
                ->take($limit)
                ->get();
        } catch (\Exception $e) {
            Log::error("Failed to fetch public posts: " . $e->getMessage());
            return self::$publicPostCache[$cacheKey] = collect();
        }
    }

    private static function homepageSection(string $section, ?array $fallbackArticles = null, int $limit = 0, array $exceptIds = []): array
    {
        $fallbackArticles ??= FallbackDataService::getArticles();

        $posts = self::sectionPosts($section, $limit, $exceptIds)
            ->map(fn(Post $post) => self::toArticleArray($post));

        if ($posts->isNotEmpty()) {
            return $posts->values()->all();
        }

        return collect(self::legacyHomepageSection($section, self::homepageArticles($fallbackArticles), $fallbackArticles))
            ->reject(fn(array $article) => self::articleIdExcluded($article, $exceptIds))
            ->take($limit)
            ->values()
            ->all();
    }

    private static function sectionPosts(string $section, int $limit, array $exceptIds = []): Collection
    {
        if (!isset(self::SECTION_COLUMNS[$section])) return collect();

        $meta = self::SECTION_COLUMNS[$section];

        if (!SchemaReadyCheck::hasSectionColumns([$meta['flag'], $meta['order']])) {
            return collect();
        }

        try {
            $scope = $meta['scope'];

            return Post::query()
                ->withContentRelations()
                ->published()
                ->{$scope}()
                ->when($exceptIds !== [], function (Builder $query) use ($exceptIds) {
                    $query->whereNotIn('id', array_values(array_unique($exceptIds)));
                })
                ->orderByRaw("CASE WHEN {$meta['order']} IS NULL THEN 1 ELSE 0 END")
                ->orderBy($meta['order'])
                ->latest('published_at')
                ->latest('id')
                ->take($limit)
                ->get();
        } catch (\Exception $e) {
            Log::error("Failed to fetch section posts for [{$section}]: " . $e->getMessage());
            return collect();
        }
    }

    private static function categoryPosts(array $categorySlugs, int $limit, ?string $division = null, ?string $district = null, ?string $upazila = null, bool $relationshipOnly = false): Collection
    {
        if (!SchemaReadyCheck::isPostsTableReady()) {
            return collect();
        }

        try {
            $hasLocationFilter = self::hasLocationFilter($division, $district, $upazila);

            if ($hasLocationFilter && !SchemaReadyCheck::hasLocationColumns()) {
                return collect();
            }

            $query = Post::query()
                // Every feed is normalized to arrays before Blade rendering, so
                // all category, author, media, and tag relationships must be
                // available here to avoid lazy loading while mapping posts.
                ->withContentRelations()
                ->whereIn('status', self::publicStatuses());

            self::applyCategoryFilter($query, $categorySlugs, $relationshipOnly);
            self::applyLocationFilter($query, $division, $district, $upazila);

            return $query->latest('published_at')
                ->latest('id')
                ->take($limit)
                ->get();
        } catch (\Exception $e) {
            Log::error("Failed to fetch category posts: " . $e->getMessage());
            return collect();
        }
    }

    private static function localNewsPosts(int $limit): Collection
    {
        if (!SchemaReadyCheck::hasLocalNewsColumns()) {
            return collect();
        }

        try {
            return Post::query()
                ->withContentRelations()
                ->with(['divisionLocation', 'districtLocation', 'upazilaLocation'])
                ->published()
                ->localLocated()
                ->orderByRaw('COALESCE(published_at, created_at) DESC')
                ->latest('id')
                ->take($limit)
                ->get();
        } catch (\Exception $e) {
            Log::error("Failed to fetch local news posts: " . $e->getMessage());
            return collect();
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

    private static function applyCategoryFilter(Builder $query, array $categorySlugs, bool $relationshipOnly = false): void
    {
        $categorySlugs = array_values(array_filter($categorySlugs));

        if ($categorySlugs === []) {
            return;
        }

        if (SchemaReadyCheck::hasCategoryRelationship()) {
            $query->where(function (Builder $categoryQuery) use ($categorySlugs) {
                $categoryQuery
                    ->whereHas('categories', fn (Builder $relationQuery) => $relationQuery->whereIn('slug', $categorySlugs))
                    ->orWhereHas('primaryCategory', fn (Builder $relationQuery) => $relationQuery->whereIn('slug', $categorySlugs));
            });

            return;
        }

        if ($relationshipOnly) {
            $query->whereRaw('1 = 0');
            return;
        }

        $query->where(function (Builder $legacyQuery) use ($categorySlugs) {
            $legacyQuery
                ->whereIn('category_slug', $categorySlugs)
                ->orWhereIn('subcategory_slug', $categorySlugs)
                ->orWhereHas('category', fn (Builder $categoryQuery) => $categoryQuery->whereIn('slug', $categorySlugs))
                ->orWhereHas('subcategory', fn (Builder $categoryQuery) => $categoryQuery->whereIn('slug', $categorySlugs));
        });
    }

    private static function applyLocationFilter(Builder $query, ?string $division = null, ?string $district = null, ?string $upazila = null): void
    {
        if (! self::hasLocationFilter($division, $district, $upazila)) {
            return;
        }

        if (SchemaReadyCheck::hasLocationIdColumns()) {
            $divisionId = self::divisionId($division);
            $districtId = self::districtId($district, $divisionId);
            $upazilaId = self::upazilaId($upazila, $districtId);

            if ($division && ! $divisionId) {
                $query->whereRaw('1 = 0');
                return;
            }

            if ($district && ! $districtId) {
                $query->whereRaw('1 = 0');
                return;
            }

            if ($upazila && ! $upazilaId) {
                $query->whereRaw('1 = 0');
                return;
            }

            $query
                ->when($divisionId, fn (Builder $query) => $query->where('division_id', $divisionId))
                ->when($districtId, fn (Builder $query) => $query->where('district_id', $districtId))
                ->when($upazilaId, fn (Builder $query) => $query->where('upazila_id', $upazilaId));

            return;
        }

        $query
            ->when($division, fn (Builder $query) => $query->where('division', $division))
            ->when($district, fn (Builder $query) => $query->where('district', $district))
            ->when($upazila, fn (Builder $query) => $query->where('upazila', $upazila));
    }

    private static function divisionId(?string $division): ?int
    {
        if (! $division) return null;

        $cacheKey = 'division:' . $division;

        if (array_key_exists($cacheKey, self::$locationIdCache)) {
            return self::$locationIdCache[$cacheKey];
        }

        try {
            return self::$locationIdCache[$cacheKey] = DB::table('divisions')
                ->where('name', $division)
                ->orWhere('name_bangla', $division)
                ->value('id');
        } catch (\Exception $e) {
            Log::error("Failed to query division ID: " . $e->getMessage());
            return self::$locationIdCache[$cacheKey] = null;
        }
    }

    private static function districtId(?string $district, ?int $divisionId): ?int
    {
        if (! $district) return null;

        $cacheKey = 'district:' . ($divisionId ?: 'any') . ':' . $district;

        if (array_key_exists($cacheKey, self::$locationIdCache)) {
            return self::$locationIdCache[$cacheKey];
        }

        try {
            return self::$locationIdCache[$cacheKey] = DB::table('districts')
                ->when($divisionId, fn ($query) => $query->where('division_id', $divisionId))
                ->where(fn ($query) => $query->where('name', $district)->orWhere('name_bangla', $district))
                ->value('id');
        } catch (\Exception $e) {
            Log::error("Failed to query district ID: " . $e->getMessage());
            return self::$locationIdCache[$cacheKey] = null;
        }
    }

    private static function upazilaId(?string $upazila, ?int $districtId): ?int
    {
        if (! $upazila) return null;

        $cacheKey = 'upazila:' . ($districtId ?: 'any') . ':' . $upazila;

        if (array_key_exists($cacheKey, self::$locationIdCache)) {
            return self::$locationIdCache[$cacheKey];
        }

        try {
            return self::$locationIdCache[$cacheKey] = DB::table('upazilas')
                ->when($districtId, fn ($query) => $query->where('district_id', $districtId))
                ->where(fn ($query) => $query->where('name', $upazila)->orWhere('name_bangla', $upazila))
                ->value('id');
        } catch (\Exception $e) {
            Log::error("Failed to query upazila ID: " . $e->getMessage());
            return self::$locationIdCache[$cacheKey] = null;
        }
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
            'image_url' => ImageResolver::postImageUrl($post),
            'views' => (int) ($post->view_count ?? 0),
            'tags' => $post->relationLoaded('tags') ? $post->tags->pluck('name')->values()->all() : [],
        ];

        if ($includeBody) {
            $article['body'] = collect(preg_split('/\R{2,}/', (string) ($post->content ?: $post->body)))
                ->map(fn(string $paragraph) => trim($paragraph))
                ->filter()
                ->values()
                ->all();
            $article['location'] = null;
            if ($post->relationLoaded('districtLocation') && $post->districtLocation) {
                $article['location'] = $post->districtLocation->name_bangla ?: $post->districtLocation->name;
            }
            $article['meta_title'] = $post->meta_title ?: $post->title;
            $article['meta_description'] = $post->meta_description ?: Str::limit(strip_tags((string) $post->excerpt), 155);
        }

        return $article;
    }
}
