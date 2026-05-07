<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
            $headlines = [
                ['title' => 'মেট্রোরেলের নতুন রুট চালু, স্বস্তিতে যাত্রীরা',                     'slug' => 'metro-rail-new-route'],
                ['title' => 'বিশ্বকাপের প্রথম ম্যাচে বাংলাদেশের দুর্দান্ত জয়',                  'slug' => 'cricket-world-cup-win'],
                ['title' => 'কৃত্রিম বুদ্ধিমত্তার নতুন চমক, চিন্তায় প্রযুক্তি বিশ্ব',            'slug' => 'ai-new-development'],
                ['title' => 'অর্থনৈতিক প্রবৃদ্ধিতে নতুন রেকর্ড, আশাবাদী বিশেষজ্ঞরা',            'slug' => 'economic-growth-report'],
                ['title' => 'রাজধানীতে আন্তর্জাতিক মানের নতুন হাসপাতাল উদ্বোধন',                 'slug' => 'new-hospital-dhaka'],
                ['title' => 'জলবায়ু সম্মেলনে বিশ্ব নেতাদের কড়া বার্তা',                        'slug' => 'international-climate-summit'],
                ['title' => 'ঈদে মুক্তি পাচ্ছে বহুল প্রতীক্ষিত সিনেমা \'স্বপ্নযাত্রা\'',        'slug' => 'new-movie-release'],
                ['title' => 'দাবি আদায়ে শিক্ষার্থীদের আন্দোলন অব্যাহত',                         'slug' => 'student-protest-update'],
                ['title' => 'দেশীয় স্টার্টআপে বিশাল বিদেশি বিনিয়োগ',                           'slug' => 'tech-startup-funding'],
                ['title' => 'কৃষিতে নতুন প্রযুক্তির ছোঁয়া, কৃষকদের মুখে হাসি',                  'slug' => 'agricultural-innovation'],
            ];

            $view->with('tickerHeadlines', $headlines);
        });
    }
}
