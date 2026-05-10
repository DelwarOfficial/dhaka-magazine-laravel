<?php

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Models\Post;
use App\Support\ArticleFeed;
use App\Support\PostCategoryResolver;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function latest()
    {
        $fallbackArticles = $this->fallbackArticles();
        $posts = $this->latestPosts($fallbackArticles);
        $topStory = $posts->firstItem() === 1 ? $posts->getCollection()->first() : null;
        $popularNews = array_slice(ArticleFeed::homepageArticles($fallbackArticles, 20), 0, 5);
        $metaTitle = 'সর্বশেষ সংবাদ | Dhaka Magazine';
        $metaDescription = 'বাংলাদেশ ও বিশ্বের সর্বশেষ খবর, রাজনীতি, খেলাধুলা, বিনোদন, অর্থনীতি ও প্রযুক্তির আপডেট পড়ুন Dhaka Magazine-এ।';
        $canonicalUrl = route('news.latest');
        $pageImage = $topStory['image_url'] ?? asset('images/dhaka-magazine-color-logo.svg');

        return view('news.latest', compact(
            'posts',
            'topStory',
            'popularNews',
            'metaTitle',
            'metaDescription',
            'canonicalUrl',
            'pageImage'
        ));
    }

    private function latestPosts(array $fallbackArticles): LengthAwarePaginator
    {
        if (! $this->postsTableReady()) {
            return $this->paginateArticles($fallbackArticles);
        }

        try {
            // All Posts is live database content. Eager loading author/category keeps
            // the existing Blade loops unchanged while avoiding N+1 queries.
            $query = Post::query()
                ->when($this->availableRelations(), fn ($query, array $relations) => $query->with($relations))
                ->whereIn('status', ['published'])
                ->latest('published_at')
                ->latest('id');

            $posts = $query
                ->paginate(20)
                ->withQueryString();

            $posts->getCollection()->transform(fn (Post $post) => $this->toNewsItem($post));

            if ($posts->total() === 0) {
                return $this->paginateArticles($fallbackArticles);
            }

            return $posts;
        } catch (\Throwable) {
            return $this->paginateArticles($fallbackArticles);
        }
    }

    private function toNewsItem(Post $post): array
    {
        $category = PostCategoryResolver::categoryFor($post);
        $publishedAt = $post->published_at ?: $post->created_at;

        return [
            'slug' => $post->slug,
            'title' => $post->title,
            'category' => $category['name_bn'] ?? PostCategoryResolver::fallbackCategory()['name_bn'],
            'category_url' => PostCategoryResolver::categoryRoute($category),
            'excerpt' => Str::limit(strip_tags((string) ($post->excerpt ?: $post->content ?: $post->body)), 170),
            'author' => $post->author?->name ?: $post->source_name ?: 'ঢাকা ম্যাগাজিন ডেস্ক',
            'date' => DateHelper::getBengaliDate($publishedAt),
            'time_ago' => DateHelper::timeAgo($publishedAt),
            'image_url' => $this->postImageUrl($post),
            'views' => $this->viewCount($post),
        ];
    }

    private function imageUrl(?string $path): string
    {
        if (! $path) {
            return asset('images/news-1.jpg');
        }

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        if (Str::startsWith($path, ['/images/', 'images/'])) {
            return asset(ltrim($path, '/'));
        }

        if (! Str::contains($path, '/') && file_exists(public_path("images/{$path}"))) {
            return asset("images/{$path}");
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    private function postImageUrl(Post $post): string
    {
        if ($post->image_path) {
            $filename = basename($post->image_path);

            return file_exists(public_path("images/{$filename}"))
                ? asset("images/{$filename}")
                : $this->placeholderImageUrl();
        }

        return $this->imageUrl($post->featured_image);
    }

    private function placeholderImageUrl(): string
    {
        foreach (['placeholder.jpg', 'news-1.jpg', 'coming-soon-ad.webp'] as $filename) {
            if (file_exists(public_path("images/{$filename}"))) {
                return asset("images/{$filename}");
            }
        }

        return asset('images/news-1.jpg');
    }

    private function viewCount(Post $post): ?int
    {
        foreach (['view_count', 'views', 'hit_count'] as $column) {
            if (isset($post->{$column}) && is_numeric($post->{$column})) {
                return (int) $post->{$column};
            }
        }

        return null;
    }

    private function availableRelations(): array
    {
        return collect(['category.parent', 'subcategory.parent', 'author'])
            ->filter(fn (string $relation) => method_exists(Post::class, explode('.', $relation)[0]))
            ->values()
            ->all();
    }

    private function fallbackArticles(): array
    {
        return app(HomeController::class)->fallbackArticles();
    }

    private function paginateArticles(array $articles): LengthAwarePaginator
    {
        $page = LengthAwarePaginator::resolveCurrentPage('page');
        $perPage = 20;
        $items = collect($articles)
            ->values()
            ->map(fn (array $article) => $article + [
                'category_url' => null,
                'views' => null,
            ]);

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );
    }

    private function postsTableReady(): bool
    {
        try {
            return class_exists(Post::class) && Schema::hasTable('posts');
        } catch (\Throwable) {
            return false;
        }
    }

}
