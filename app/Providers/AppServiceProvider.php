<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use App\Models\ContentPlacement;
use App\Models\Post;
use App\Support\CategoryRepository;
use App\Support\ArticleFeed;
use App\Support\FallbackDataService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerContentCacheInvalidation();

        /*
        |------------------------------------------------------------------
        | Share ticker headlines with the layout (for scroll-nav component)
        |------------------------------------------------------------------
        |
        | This View Composer runs whenever layouts.app is rendered.
        | It picks the 10 most recent headlines and passes them as
        | $tickerHeadlines to every page.
        |
        | When you switch to a real database, replace the body with:
        |     $headlines = \App\Models\Article::latest()
        |         ->take(10)
        |         ->get(['title', 'slug'])
        |         ->toArray();
        |
        */
        View::composer('layouts.app', function ($view) {
            $view->with('tickerHeadlines', Cache::remember(
                'layout:ticker-headlines:v1',
                now()->addSeconds((int) config('homepage.cache.ttl', 300)),
                fn () => ArticleFeed::breakingNews(FallbackDataService::getArticles(), 10),
            ));

            $view->with('siteCategories', Cache::remember(
                'layout:site-categories:v2',
                now()->addSeconds((int) config('homepage.cache.ttl', 300)),
                fn () => CategoryRepository::parents(),
            ));
        });
    }

    private function registerContentCacheInvalidation(): void
    {
        $flushHomepage = function (): void {
            Cache::forget(config('homepage.cache.key', 'homepage:v1'));
            Cache::forget('layout:ticker-headlines:v1');
        };

        Post::saved(fn ($model = null) => $flushHomepage());
        Post::deleted(fn ($model = null) => $flushHomepage());

        ContentPlacement::saved(fn ($model = null) => $flushHomepage());
        ContentPlacement::deleted(fn ($model = null) => $flushHomepage());

        $flushCategories = function () use ($flushHomepage): void {
            $flushHomepage();
            Cache::forget('layout:site-categories:v1');
            Cache::forget('layout:site-categories:v2');
        };

        Category::saved(fn ($model = null) => $flushCategories());
        Category::deleted(fn ($model = null) => $flushCategories());
    }
}
