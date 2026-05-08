<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Support\ArticleFeed;
use App\Support\CategoryRepository;
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
        $category = CategoryRepository::flat()->firstWhere('slug', $post->subcategory_slug ?: $post->category_slug)
            ?: CategoryRepository::flat()->firstWhere('slug', $post->category_slug);
        $publishedAt = $post->published_at ?: $post->created_at;

        return [
            'slug' => $post->slug,
            'title' => $post->title,
            'category' => $category['name_bn'] ?? $post->subcategory_slug ?? $post->category_slug,
            'category_url' => $category ? CategoryRepository::route($category) : null,
            'excerpt' => Str::limit(strip_tags((string) ($post->excerpt ?: $post->body)), 170),
            'author' => $post->source_name ?: 'ঢাকা ম্যাগাজিন ডেস্ক',
            'date' => optional($publishedAt)->format('d M, Y'),
            'time_ago' => optional($publishedAt)->diffForHumans(),
            'image_url' => $this->imageUrl($post->featured_image),
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

        return asset('storage/' . ltrim($path, '/'));
    }

    private function viewCount(Post $post): ?int
    {
        foreach (['views', 'view_count', 'hit_count'] as $column) {
            if (isset($post->{$column}) && is_numeric($post->{$column})) {
                return (int) $post->{$column};
            }
        }

        return null;
    }

    private function availableRelations(): array
    {
        return collect(['category', 'author'])
            ->filter(fn (string $relation) => method_exists(Post::class, $relation))
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
