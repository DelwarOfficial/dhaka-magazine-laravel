<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use App\Models\ContentPlacement;
use App\Models\Post;
use App\Services\PopularNewsService;
use App\Services\TickerHeadlineService;
use App\Support\CategoryRepository;

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

        View::composer('layouts.app', function ($view) {
            $view->with('tickerHeadlines', app(TickerHeadlineService::class)->get());

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
            TickerHeadlineService::forget();
            PopularNewsService::forget();
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
