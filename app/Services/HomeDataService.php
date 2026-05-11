<?php

namespace App\Services;

use App\Models\District;
use App\Support\ArticleFeed;
use App\Support\FallbackDataService;
use Illuminate\Support\Facades\File;

class HomeDataService
{
    public function getHomepageData(): array
    {
        $articles = ArticleFeed::homepageArticles(FallbackDataService::getArticles());
        $breakingStories = ArticleFeed::breakingNews(FallbackDataService::getArticles(), 10);
        $usedHomepagePostIds = $this->articleIds($breakingStories);

        $categories = [
            'জাতীয়', 'রাজনীতি', 'অর্থনীতি', 'country', 'বিশ্ব',
            'খেলা', 'বিনোদন', 'লাইফস্টাইল', 'মতামত', 'প্রযুক্তি',
        ];

        // ══ HERO — 3 COLUMNS ═══════════════════════════════════════
        $featured = ArticleFeed::featured(FallbackDataService::getArticles(), 1, $usedHomepagePostIds)[0] ?? null;
        $usedHomepagePostIds = $this->mergeArticleIds($usedHomepagePostIds, [$featured]);

        $centerGrid = ArticleFeed::sticky(FallbackDataService::getArticles(), 6, $usedHomepagePostIds);
        $usedHomepagePostIds = $this->mergeArticleIds($usedHomepagePostIds, $centerGrid);

        $leftCol = ArticleFeed::trending(FallbackDataService::getArticles(), 5, $usedHomepagePostIds);
        $usedHomepagePostIds = $this->mergeArticleIds($usedHomepagePostIds, $leftCol);

        $rightCol = ArticleFeed::editorsPick(FallbackDataService::getArticles(), 3, $usedHomepagePostIds);
        $usedHomepagePostIds = $this->mergeArticleIds($usedHomepagePostIds, $rightCol);

        // ══ BANGLADESH ════════════════════════════════════════════
        $bangladeshArticles = $this->categoryArticles(['bangladesh', 'national', 'dhaka', 'crime', 'accidents', 'law-justice', 'politics'], 4);

        // ══ Local News / সারাদেশ ══════════════════════════════
        $localNewsArticles = ArticleFeed::localNews(FallbackDataService::getArticles(), 9);
        $countryLeft = array_slice($localNewsArticles, 0, 2);
        $countryHero = $localNewsArticles[2] ?? null;
        $countryRight = array_slice($localNewsArticles, 3, 6);

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
        $specialArticles = $this->categoryArticles(['dhaka-magazine-special'], 5);

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
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to load saradesh divisions: " . $e->getMessage());
            $saradeshDivisions = [];
        }

        return compact(
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
        );
    }

    public function getPhotoStoryData(): array
    {
        return $this->buildPhotoStoryPayload(ArticleFeed::homepageArticles(FallbackDataService::getArticles()));
    }

    private function categoryArticles(array $slugs, int $limit): array
    {
        return ArticleFeed::categoryArticles($slugs, FallbackDataService::getArticles(), $limit);
    }

    private function firstCategoryArticle(array $slugs, array $fallback): array
    {
        return $this->categoryArticles($slugs, 1)[0] ?? $fallback;
    }

    private function mergeArticleIds(array $ids, array $articles): array
    {
        return array_values(array_unique(array_merge($ids, $this->articleIds($articles))));
    }

    private function articleIds(array $articles): array
    {
        return collect($articles)
            ->filter()
            ->pluck('id')
            ->filter(fn($id) => filled($id))
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();
    }

    private function buildPhotoStoryPayload(array $articles): array
    {
        $carousel = collect($articles)->take(10)->values()->map(function ($article, $index) {
            return [
                'id' => $article['id'] ?? $index + 1,
                'headline' => $article['title'],
                'slug' => $article['slug'],
                'timestamp' => $article['time_ago'],
                'image_url' => $article['image_url'],
                'tags' => [],
            ];
        });

        if ($carousel->isEmpty()) {
            $carousel = $this->publicImageFallbackSlides();
        }

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
            'carousel' => $carousel->values()->all(),
            'latest' => $latest,
            'popular' => $popular,
        ];
    }

    private function publicImageFallbackSlides(): \Illuminate\Support\Collection
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        return collect(File::files(public_path('images')))
            ->filter(fn($file) => in_array(strtolower($file->getExtension()), $allowedExtensions, true))
            ->sortBy(fn($file) => $file->getFilename())
            ->take(5)
            ->values()
            ->map(fn($file, $index) => [
                'id' => $index + 1,
                'headline' => 'Placeholder',
                'slug' => '#',
                'timestamp' => '',
                'image_url' => asset('images/' . $file->getFilename()),
                'tags' => [],
            ]);
    }
}
