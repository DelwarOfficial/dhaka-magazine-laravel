<?php

namespace App\Support;

use App\Helpers\DateHelper;
use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ArticleFeed
{
    public static function homepageArticles(array $fallbackArticles, int $limit = 40): array
    {
        return self::publicPosts()
            ->take($limit)
            ->map(fn(Post $post) => self::toArticleArray($post))
            ->concat($fallbackArticles)
            ->take(max($limit, count($fallbackArticles)))
            ->values()
            ->all();
    }

    public static function categoryArticles(array $categorySlugs, array $fallbackArticles, int $limit = 30): array
    {
        $posts = self::categoryPosts($categorySlugs, $limit)
            ->map(fn(Post $post) => self::toArticleArray($post));

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
                ->with(['category.parent', 'subcategory.parent'])
                ->whereIn('status', self::publicStatuses())
                ->latest('published_at')
                ->latest('id')
                ->take(100)
                ->get();
        } catch (\Throwable) {
            return collect();
        }
    }

    private static function categoryPosts(array $categorySlugs, int $limit): Collection
    {
        if (!self::postsTableReady()) {
            return collect();
        }

        try {
            return Post::query()
                ->with(['category.parent', 'subcategory.parent'])
                ->whereIn('status', self::publicStatuses())
                ->where(function ($query) use ($categorySlugs) {
                    $query
                        ->whereIn('category_slug', $categorySlugs)
                        ->orWhereIn('subcategory_slug', $categorySlugs)
                        ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->whereIn('slug', $categorySlugs))
                        ->orWhereHas('subcategory', fn ($categoryQuery) => $categoryQuery->whereIn('slug', $categorySlugs));
                })
                ->latest('published_at')
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

    private static function publicStatuses(): array
    {
        return ['published'];
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
            'excerpt' => $post->excerpt,
            'author' => $post->source_name ?: 'ঢাকা ম্যাগাজিন ডেস্ক',
            'date' => DateHelper::getBengaliDate($publishedAt),
            'time_ago' => DateHelper::timeAgo($publishedAt),
            'image_url' => self::imageUrl($post->featured_image),
            'tags' => [],
        ];

        if ($includeBody) {
            $article['body'] = collect(preg_split('/\R{2,}/', (string) $post->body))
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
}
