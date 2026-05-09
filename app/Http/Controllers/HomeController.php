<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\District;
use App\Support\ArticleFeed;

class HomeController extends Controller
{
    public function index()
    {
        $articles = ArticleFeed::homepageArticles($this->sampleArticles());

        // Breaking stories (header)
        $breakingStories = [
            $articles[1],
            $articles[2],
            $articles[3],
        ];

        $categories = [
            'জাতীয়', 'রাজনীতি', 'অর্থনীতি', 'country', 'বিশ্ব',
            'খেলা', 'বিনোদন', 'লাইফস্টাইল', 'মতামত', 'প্রযুক্তি',
        ];

        // ══ HERO — 3 COLUMNS ═══════════════════════════════════════
        $leftCol    = [$articles[4], $articles[7], $articles[10], $articles[16], $articles[19]];
        $featured   = $articles[0];
        $centerGrid = [$articles[1], $articles[2], $articles[6], $articles[7], $articles[8], $articles[3]];
        $rightCol   = [$articles[5], $articles[9], $articles[3]];

        // ══ BANGLADESH ════════════════════════════════════════════
        $bangladeshArticles = $this->categoryArticles(['bangladesh', 'national', 'dhaka', 'crime', 'accidents', 'law-justice', 'politics'], 4);

        // ══ সারাদেশ → Country ═════════════════════════════════
        $countryLeft  = [$articles[18], $articles[10]];
        $countryHero  = $articles[6];
        $countryRight = [$articles[4], $articles[15], $articles[19], $articles[8], $articles[2], $articles[11]];

        // ══ INTERNATIONAL ════════════════════════════════════════
        $worldArticles = $this->categoryArticles(['world'], 6);
        $internationalBig = $worldArticles[0] ?? $articles[5];
        $internationalSmall = array_slice($worldArticles, 1, 5);

        // ══ OPINION ══════════════════════════════════════════════
        $opinionArticles = $this->categoryArticles(['politics'], 7);
        $opinionMeta = [
            ['name' => 'ড. শফিকুল ইসলাম',    'tag' => 'কলাম'],
            ['name' => 'সৈয়দ আবুল মকসুদ',   'tag' => 'মতামত'],
            ['name' => 'অধ্যাপক আনু মুহাম্মদ', 'tag' => 'বিশ্লেষণ'],
            ['name' => 'ফারুক ওয়াসিফ',       'tag' => 'মতামত'],
        ];

        // ══ SPORTS ═══════════════════════════════════════════════
        $sportsArticles = $this->categoryArticles(['sports', 'football', 'cricket', 'other-sports'], 4);
        $sportsSubcatArticles = [
            ['article' => $this->firstCategoryArticle(['cricket'], $sportsArticles[0] ?? $articles[1]), 'subcat' => 'ক্রিকেট'],
            ['article' => $this->firstCategoryArticle(['other-sports'], $sportsArticles[1] ?? $articles[11]), 'subcat' => 'অন্যান্য খেলা'],
            ['article' => $this->firstCategoryArticle(['football'], $sportsArticles[2] ?? $articles[3]), 'subcat' => 'ফুটবল'],
            ['article' => $this->firstCategoryArticle(['sports'], $sportsArticles[3] ?? $articles[13]), 'subcat' => 'আজকের খেলা'],
        ];

        // ══ OPINION / মতামত ═════════════════════════════════════════════════
        $matamatArticles = $this->categoryArticles(['opinion'], 4);

        // ══ VIDEO ════════════════════════════════════════════════
        $videoArticles = $this->categoryArticles(['videos'], 4);
        $videoFeatured = $videoArticles[0] ?? $articles[6];
        $videoSmall = array_slice($videoArticles, 1, 3);

        // ══ ENTERTAINMENT ════════════════════════════════════════
        $entertainmentArticles = $this->categoryArticles(['entertainment'], 7);
        $entertainmentLeft = array_slice($entertainmentArticles, 0, 3);
        $entertainmentHero = $entertainmentArticles[3] ?? $articles[7];
        $entertainmentRight = array_slice($entertainmentArticles, 4, 3);

        // ══ ECONOMY + HEALTH + JOBS ═════════════════════════════
        $economyArticles = $this->categoryArticles(['economy', 'stock-market', 'banking-insurance', 'industry', 'agriculture'], 4);
        $healthArticles = $this->categoryArticles(['lifestyle', 'health', 'beauty', 'recipes'], 4);
        $jobArticles = $this->categoryArticles(['jobs', 'government-jobs', 'private-jobs'], 4);

        // ══ SPECIAL ══════════════════════════════════════════════
        $specialArticles = [$articles[7], $articles[16], $articles[0], $articles[17], $articles[19]];

        // ══ POPULAR NEWS (sidebar) ═══════════════════════════════
        $popularNews = array_slice($articles, 5, 5);

        // ══ PHOTO NEWS (local images driven) ════════════════════
        $photoStoryPayload = $this->buildPhotoStoryPayload($articles);
        $photoNewsArticles = $photoStoryPayload['carousel'];
        $photoNewsLatest   = $photoStoryPayload['latest'];
        $photoNewsPopular  = $photoStoryPayload['popular'];

        // ══ BOTTOM 4-COL BLOCK (ধর্ম, রাজধানী, শিক্ষা, প্রবাস) ════
        $religionArticles = $this->categoryArticles(['religion'], 4);
        $rajdhaniArticles = $this->categoryArticles(['dhaka'], 4);
        $educationArticles = $this->categoryArticles(['education'], 4);
        $probashArticles = $this->categoryArticles(['expatriates'], 4);

        // ══ SARADESH FILTER — load divisions for the জেলার সংবাদ dropdown ═══
        try {
            $saradeshDivisions = District::allDivisions();
        } catch (\Throwable) {
            $saradeshDivisions = [];
        }

        return view('pages.home', compact(
            'breakingStories',
            'categories',
            'leftCol',
            'featured',
            'centerGrid',
            'rightCol',
            'bangladeshArticles',
            'countryLeft',
            'countryHero',
            'countryRight',
            'internationalBig',
            'internationalSmall',
            'opinionArticles',
            'opinionMeta',
            'sportsArticles',
            'sportsSubcatArticles',
            'matamatArticles',
            'videoFeatured',
            'videoSmall',
            'entertainmentLeft',
            'entertainmentHero',
            'entertainmentRight',
            'economyArticles',
            'healthArticles',
            'jobArticles',
            'specialArticles',
            'popularNews',
            'photoNewsArticles',
            'photoNewsLatest',
            'photoNewsPopular',
            'photoStoryPayload',
            'religionArticles',
            'rajdhaniArticles',
            'educationArticles',
            'probashArticles',
            'saradeshDivisions'
        ));
    }

    public function photoStoryData()
    {
        return response()->json($this->buildPhotoStoryPayload(ArticleFeed::homepageArticles($this->sampleArticles())));
    }

    private function categoryArticles(array $slugs, int $limit): array
    {
        return ArticleFeed::categoryArticles($slugs, $this->sampleArticles(), $limit);
    }

    private function firstCategoryArticle(array $slugs, array $fallback): array
    {
        return $this->categoryArticles($slugs, 1)[0] ?? $fallback;
    }

    public function fallbackArticles(): array
    {
        return $this->sampleArticles();
    }

    private function buildPhotoStoryPayload(array $articles): array
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $imageFiles = collect(File::files(public_path('images')))
            ->filter(fn($file) => in_array(strtolower($file->getExtension()), $allowedExtensions, true))
            ->sortBy(fn($file) => $file->getFilename())
            ->values();

        if ($imageFiles->isEmpty()) {
            $imageFiles = collect(['a.jpg', 'b.jpg', 'c.jpg'])->map(fn($name) => new \SplFileInfo(public_path('images/' . $name)));
        }

        $carousel = $imageFiles->take(10)->values()->map(function ($file, $index) use ($articles) {
            $article = $articles[$index % count($articles)];
            $filename = $file->getFilename();

            return [
                'id' => $index + 1,
                'headline' => $article['title'],
                'slug' => $article['slug'],
                'timestamp' => $article['time_ago'],
                'image_url' => asset('images/' . $filename),
                'tags' => [],
            ];
        })->all();

        $latest = collect($articles)->take(8)->values()->map(function ($article, $index) {
            return [
                'id' => $index + 1,
                'headline' => $article['title'],
                'slug' => $article['slug'],
                'timestamp' => $article['time_ago'],
            ];
        })->all();

        $popular = collect($articles)->slice(8, 8)->values()->map(function ($article, $index) {
            return [
                'id' => $index + 1,
                'headline' => $article['title'],
                'slug' => $article['slug'],
                'timestamp' => $article['time_ago'],
            ];
        })->all();

        return [
            'carousel' => $carousel,
            'latest' => $latest,
            'popular' => $popular,
        ];
    }

    private function sampleArticles()
    {
        $img = fn($n) => asset("images/news-{$n}.jpg");

        return [
            [
                'id' => 1, 'slug' => 'metro-rail-new-route',
                'title' => 'মেট্রোরেলের নতুন রুট চালু, স্বস্তিতে যাত্রীরা',
                'category' => 'জাতীয়',
                'excerpt' => 'যানজটের নগরী ঢাকায় স্বস্তির এক নতুন দ্বার উন্মোচিত হলো। আজ সকালে মেট্রোরেলের নতুন রুটের উদ্বোধন করা হয়েছে।',
                'author' => 'নিজস্ব প্রতিবেদক', 'date' => '১২ মে, ২০২৪', 'time_ago' => '৩০ মিনিট আগে',
                'image_url' => $img(1),
                'tags' => [],
            ],
            [
                'id' => 2, 'slug' => 'cricket-world-cup-win',
                'title' => 'বিশ্বকাপের প্রথম ম্যাচে বাংলাদেশের দুর্দান্ত জয়',
                'category' => 'খেলা',
                'excerpt' => 'টানটান উত্তেজনার এক ম্যাচে প্রতিপক্ষকে বড় ব্যবধানে হারিয়েছে বাংলাদেশ ক্রিকেট দল।',
                'author' => 'ক্রীড়া প্রতিবেদক', 'date' => '১১ মে, ২০২৪', 'time_ago' => '১ ঘণ্টা আগে',
                'image_url' => $img(2),
                'tags' => [],
            ],
            [
                'id' => 3, 'slug' => 'ai-new-development',
                'title' => 'কৃত্রিম বুদ্ধিমত্তার নতুন চমক, চিন্তায় প্রযুক্তি বিশ্ব',
                'category' => 'প্রযুক্তি',
                'excerpt' => 'সম্প্রতি এক নতুন এআই মডেল উন্মোচন করা হয়েছে যা মানুষের মতো চিন্তা করতে সক্ষম বলে দাবি করা হচ্ছে।',
                'author' => 'প্রযুক্তি ডেস্ক', 'date' => '১০ মে, ২০২৪', 'time_ago' => '২ ঘণ্টা আগে',
                'image_url' => $img(3),
                'tags' => [],
            ],
            [
                'id' => 4, 'slug' => 'economic-growth-report',
                'title' => 'অর্থনৈতিক প্রবৃদ্ধিতে নতুন রেকর্ড, আশাবাদী বিশেষজ্ঞরা',
                'category' => 'অর্থনীতি',
                'excerpt' => 'চলতি অর্থবছরে দেশের অর্থনৈতিক প্রবৃদ্ধি সকল রেকর্ড ছাড়িয়ে গেছে। রপ্তানি আয়েও দেখা গেছে বড় লাফ।',
                'author' => 'বাণিজ্য প্রতিবেদক', 'date' => '০৯ মে, ২০২৪', 'time_ago' => '৩ ঘণ্টা আগে',
                'image_url' => $img(4),
                'tags' => [],
            ],
            [
                'id' => 5, 'slug' => 'new-hospital-dhaka',
                'title' => 'রাজধানীতে আন্তর্জাতিক মানের নতুন হাসপাতাল উদ্বোধন',
                'category' => 'লাইফস্টাইল',
                'excerpt' => 'সর্বাধুনিক চিকিৎসাসুবিধা নিয়ে ঢাকায় যাত্রা শুরু করলো এক নতুন হাসপাতাল।',
                'author' => 'স্বাস্থ্য প্রতিবেদক', 'date' => '০৮ মে, ২০২৪', 'time_ago' => '৪ ঘণ্টা আগে',
                'image_url' => $img(5),
                'tags' => [],
            ],
            [
                'id' => 6, 'slug' => 'international-climate-summit',
                'title' => 'জলবায়ু সম্মেলনে বিশ্ব নেতাদের কড়া বার্তা',
                'category' => 'বিশ্ব',
                'excerpt' => 'বৈশ্বিক উষ্ণতা রোধে এখনই কার্যকর পদক্ষেপ না নিলে ভয়াবহ পরিণতির সতর্কবার্তা দিয়েছেন বিশ্ব নেতারা।',
                'author' => 'আন্তর্জাতিক ডেস্ক', 'date' => '০৭ মে, ২০২৪', 'time_ago' => '৫ ঘণ্টা আগে',
                'image_url' => $img(6),
                'tags' => [],
            ],
            [
                'id' => 7, 'slug' => 'new-movie-release',
                'title' => 'ঈদে মুক্তি পাচ্ছে বহুল প্রতীক্ষিত সিনেমা \'স্বপ্নযাত্রা\'',
                'category' => 'বিনোদন',
                'excerpt' => 'দীর্ঘদিন ধরে আলোচনায় থাকা সিনেমা \'স্বপ্নযাত্রা\' অবশেষে এই ঈদে প্রেক্ষাগৃহে আসছে।',
                'author' => 'বিনোদন ডেস্ক', 'date' => '০৬ মে, ২০২৪', 'time_ago' => '৬ ঘণ্টা আগে',
                'image_url' => $img(7),
                'tags' => [],
            ],
            [
                'id' => 8, 'slug' => 'student-protest-update',
                'title' => 'দাবি আদায়ে শিক্ষার্থীদের আন্দোলন অব্যাহত',
                'category' => 'জাতীয়',
                'excerpt' => 'নিরাপদ সড়কের দাবিতে শিক্ষার্থীদের আন্দোলন আজ তৃতীয় দিনে গড়িয়েছে।',
                'author' => 'নিজস্ব প্রতিবেদক', 'date' => '০৫ মে, ২০২৪', 'time_ago' => '৭ ঘণ্টা আগে',
                'image_url' => $img(8),
                'tags' => [],
            ],
            [
                'id' => 9, 'slug' => 'opinion-education-system',
                'title' => 'শিক্ষা ব্যবস্থায় পরিবর্তন: কতটা জরুরি?',
                'category' => 'মতামত',
                'excerpt' => 'বর্তমান শিক্ষা ব্যবস্থা কি যুগের চাহিদার সাথে তাল মেলাতে পারছে? এই নিয়ে বিস্তারিত আলোচনা।',
                'author' => 'ড. শফিকুল ইসলাম', 'date' => '০৪ মে, ২০২৪', 'time_ago' => '৮ ঘণ্টা আগে',
                'image_url' => $img(1),
                'tags' => [],
            ],
            [
                'id' => 10, 'slug' => 'tech-startup-funding',
                'title' => 'দেশীয় স্টার্টআপে বিশাল বিদেশি বিনিয়োগ',
                'category' => 'প্রযুক্তি',
                'excerpt' => 'বাংলাদেশের এক তরুণ স্টার্টআপ কোম্পানি বিদেশি বিনিয়োগকারীদের কাছ থেকে বিশাল অঙ্কের তহবিল সংগ্রহ করেছে।',
                'author' => 'প্রযুক্তি প্রতিবেদক', 'date' => '০৩ মে, ২০২৪', 'time_ago' => '৯ ঘণ্টা আগে',
                'image_url' => $img(3),
                'tags' => [],
            ],
            [
                'id' => 11, 'slug' => 'agricultural-innovation',
                'title' => 'কৃষিতে নতুন প্রযুক্তির ছোঁয়া, কৃষকদের মুখে হাসি',
                'category' => 'জাতীয়',
                'excerpt' => 'আধুনিক কৃষি যন্ত্রপাতি ব্যবহারের ফলে উৎপাদন বেড়েছে বহুগুণ, যার ফলে কৃষকরা আর্থিকভাবে লাভবান হচ্ছেন।',
                'author' => 'কৃষি প্রতিবেদক', 'date' => '০২ মে, ২০২৪', 'time_ago' => '১০ ঘণ্টা আগে',
                'image_url' => $img(4),
                'tags' => [],
            ],
            [
                'id' => 12, 'slug' => 'olympic-preparation',
                'title' => 'আগামী অলিম্পিকের জন্য প্রস্তুতি শুরু',
                'category' => 'খেলা',
                'excerpt' => 'আগামী অলিম্পিক গেমসে ভালো ফলাফল করার লক্ষ্যে এখন থেকেই প্রস্তুতি শুরু করেছে অ্যাথলেটরা।',
                'author' => 'ক্রীড়া ডেস্ক', 'date' => '০১ মে, ২০২৪', 'time_ago' => '১১ ঘণ্টা আগে',
                'image_url' => $img(2),
                'tags' => [],
            ],
            [
                'id' => 13, 'slug' => 'global-market-crisis',
                'title' => 'বিশ্ববাজারে অস্থিরতা, প্রভাব পড়ছে দেশের অর্থনীতিতে',
                'category' => 'অর্থনীতি',
                'excerpt' => 'বিশ্ববাজারে জ্বালানি তেল এবং নিত্যপ্রয়োজনীয় পণ্যের দাম বৃদ্ধিতে দেশের অর্থনীতিতে নেতিবাচক প্রভাব পড়ছে।',
                'author' => 'অর্থনীতি ডেস্ক', 'date' => '৩০ এপ্রিল, ২০২৪', 'time_ago' => '১২ ঘণ্টা আগে',
                'image_url' => $img(4),
                'tags' => [],
            ],
            [
                'id' => 14, 'slug' => 'new-smart-phone-launch',
                'title' => 'বাজারে এলো নতুন ফ্লাগশিপ স্মার্টফোন',
                'category' => 'প্রযুক্তি',
                'excerpt' => 'অত্যাধুনিক সব ফিচার নিয়ে বাজারে এসেছে নতুন একটি ফ্লাগশিপ স্মার্টফোন, যা প্রযুক্তিপ্রেমীদের মাঝে ব্যাপক আগ্রহ তৈরি করেছে।',
                'author' => 'গ্যাজেট রিভিউয়ার', 'date' => '২৯ এপ্রিল, ২০২৪', 'time_ago' => '১৩ ঘণ্টা আগে',
                'image_url' => $img(3),
                'tags' => [],
            ],
            [
                'id' => 15, 'slug' => 'music-concert-dhaka',
                'title' => 'ঢাকায় অনুষ্ঠিত হলো বিশাল কনসার্ট',
                'category' => 'বিনোদন',
                'excerpt' => 'দেশি-বিদেশি জনপ্রিয় শিল্পীদের অংশগ্রহণে ঢাকায় একটি বিশাল কনসার্ট অনুষ্ঠিত হয়েছে, যেখানে তরুণদের উপচে পড়া ভিড় ছিল।',
                'author' => 'কালচারাল ডেস্ক', 'date' => '২৮ এপ্রিল, ২০২৪', 'time_ago' => '১ দিন আগে',
                'image_url' => $img(7),
                'tags' => [],
            ],
            [
                'id' => 16, 'slug' => 'health-tips-summer',
                'title' => 'গরমের তীব্রতায় সুস্থ থাকার উপায়',
                'category' => 'লাইফস্টাইল',
                'excerpt' => 'প্রচণ্ড গরমে সুস্থ থাকতে চিকিৎসকদের কিছু জরুরি পরামর্শ মেনে চলা প্রয়োজন।',
                'author' => 'স্বাস্থ্য পরামর্শক', 'date' => '২৭ এপ্রিল, ২০২৪', 'time_ago' => '১ দিন আগে',
                'image_url' => $img(5),
                'tags' => [],
            ],
            [
                'id' => 17, 'slug' => 'opinion-traffic-jam',
                'title' => 'ঢাকার যানজট: সমাধান কোথায়?',
                'category' => 'মতামত',
                'excerpt' => 'রাজধানীর অন্যতম প্রধান সমস্যা যানজট। এর সমাধানে কী কী পদক্ষেপ নেওয়া যেতে পারে, তা নিয়ে বিশেষজ্ঞদের মতামত।',
                'author' => 'সৈয়দ আবুল মকসুদ', 'date' => '২৬ এপ্রিল, ২০২৪', 'time_ago' => '২ দিন আগে',
                'image_url' => $img(1),
                'tags' => [],
            ],
            [
                'id' => 18, 'slug' => 'international-peace-treaty',
                'title' => 'দীর্ঘদিনের সংঘাত শেষে দুই দেশের মধ্যে শান্তি চুক্তি',
                'category' => 'বিশ্ব',
                'excerpt' => 'অবশেষে দুই প্রতিবেশী দেশের মধ্যে দীর্ঘদিনের সীমান্ত সংঘাতের অবসান ঘটিয়ে একটি ঐতিহাসিক শান্তি চুক্তি স্বাক্ষরিত হয়েছে।',
                'author' => 'আন্তর্জাতিক সম্পর্ক বিশ্লেষক', 'date' => '২৫ এপ্রিল, ২০২৪', 'time_ago' => '২ দিন আগে',
                'image_url' => $img(6),
                'tags' => [],
            ],
            [
                'id' => 19, 'slug' => 'national-award-ceremony',
                'title' => 'জাতীয় চলচ্চিত্র পুরস্কার প্রদান অনুষ্ঠান সম্পন্ন',
                'category' => 'জাতীয়',
                'excerpt' => 'চলচ্চিত্র শিল্পে বিশেষ অবদানের স্বীকৃতিস্বরূপ আজ প্রধানমন্ত্রী বিজয়ীদের হাতে জাতীয় চলচ্চিত্র পুরস্কার তুলে দিয়েছেন।',
                'author' => 'সংস্কৃতি বিষয়ক প্রতিবেদক', 'date' => '২৪ এপ্রিল, ২০২৪', 'time_ago' => '৩ দিন আগে',
                'image_url' => $img(7),
                'tags' => [],
            ],
            [
                'id' => 20, 'slug' => 'new-bridge-inauguration',
                'title' => 'দেশের দক্ষিণাঞ্চলে নতুন সেতুর উদ্বোধন',
                'category' => 'জাতীয়',
                'excerpt' => 'যোগাযোগ ব্যবস্থায় নতুন মাইলফলক। দক্ষিণাঞ্চলের মানুষের দীর্ঘদিনের স্বপ্নের নতুন সেতু আজ উন্মুক্ত করা হলো।',
                'author' => 'উন্নয়ন প্রতিবেদক', 'date' => '২৩ এপ্রিল, ২০২৪', 'time_ago' => '৩ দিন আগে',
                'image_url' => $img(1),
                'tags' => [],
            ],
        ];
    }
}
