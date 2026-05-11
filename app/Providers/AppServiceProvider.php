<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
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
            // The ticker is controlled by the Breaking News flag. The hard-coded
            // list remains only as a legacy fallback for fresh or unmapped databases.
            $view->with('tickerHeadlines', ArticleFeed::breakingNews(FallbackDataService::getArticles(), 10));
            $view->with('siteCategories', CategoryRepository::parents());
        });
    }
}
