<?php

namespace App\Http\Controllers;

use App\Support\CategoryRepository;
use App\Support\ArticleFeed;
use App\Models\District;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function showParent(Request $request, string $parentSlug)
    {
        if ($target = CategoryRepository::redirectTarget($parentSlug)) {
            return redirect('/category/' . $target, 301);
        }

        $category = CategoryRepository::findParent($parentSlug);

        abort_unless($category !== null, 404);

        $categorySlugs = collect($category['children'])->pluck('slug')->push($category['slug'])->all();
        
        $division = null;
        $district = null;
        $upazila = null;
        $divisions = [];
        
        if ($parentSlug === 'country-news') {
            $division = $request->input('division', '');
            $district = $request->input('district', '');
            $upazila = $request->input('upazila', '');
            
            try {
                $divisions = District::allDivisions();
            } catch (\Throwable) {
                // Ignore
            }
        }

        return $this->renderCategory(
            $category,
            ArticleFeed::categoryArticles($categorySlugs, $this->sampleArticles(), 30, $division, $district, $upazila),
            [
                ['title' => 'হোম', 'url' => route('home')],
                ['title' => $category['name_bn'], 'url' => CategoryRepository::route($category)],
            ],
            $division,
            $district,
            $upazila,
            $divisions
        );
    }

    public function showChild(Request $request, string $parentSlug, string $childSlug)
    {
        $parent = CategoryRepository::findParent($parentSlug);
        $category = CategoryRepository::findChild($parentSlug, $childSlug);

        abort_unless($parent && $category, 404);

        return $this->renderCategory(
            $category,
            ArticleFeed::categoryArticles([$category['slug']], $this->sampleArticles()),
            [
                ['title' => 'হোম', 'url' => route('home')],
                ['title' => $parent['name_bn'], 'url' => CategoryRepository::route($parent)],
                ['title' => $category['name_bn'], 'url' => CategoryRepository::route($category)],
            ]
        );
    }

    public function sitemap()
    {
        $urls = CategoryRepository::flat()
            ->map(fn(array $category) => [
                'loc' => CategoryRepository::route($category),
                'lastmod' => now()->toDateString(),
            ]);

        return response()
            ->view('pages.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }

    private function renderCategory(array $category, array $categoryArticles, array $breadcrumbs, ?string $division = null, ?string $district = null, ?string $upazila = null, array $divisions = [])
    {
        $popularNews = array_slice(ArticleFeed::homepageArticles($this->sampleArticles()), 0, 5);
        $categoryName = $category['name_bn'];
        
        // Build meta title/description if location filtered
        if ($upazila) {
            $metaTitle = "{$upazila}-এর সর্বশেষ সংবাদ | Dhaka Magazine";
            $metaDescription = "বাংলাদেশের {$upazila} উপজেলার সর্বশেষ খবর পড়ুন Dhaka Magazine-এ।";
        } elseif ($district) {
            $metaTitle = "{$district} জেলার সর্বশেষ সংবাদ | Dhaka Magazine";
            $metaDescription = "বাংলাদেশের {$district} জেলার সর্বশেষ খবর পড়ুন Dhaka Magazine-এ।";
        } elseif ($division) {
            $metaTitle = "{$division} বিভাগের সর্বশেষ সংবাদ | Dhaka Magazine";
            $metaDescription = "বাংলাদেশের {$division} বিভাগের সর্বশেষ খবর পড়ুন Dhaka Magazine-এ।";
        } else {
            $metaTitle = $category['meta_title'];
            $metaDescription = $category['meta_description'];
        }
        
        $canonicalUrl = CategoryRepository::route($category);
        $pageImage = $categoryArticles[0]['image_url'] ?? asset('images/dhaka-magazine-color-logo.svg');

        return view('pages.category', compact(
            'category',
            'categoryName',
            'categoryArticles',
            'popularNews',
            'breadcrumbs',
            'metaTitle',
            'metaDescription',
            'canonicalUrl',
            'pageImage',
            'division',
            'district',
            'upazila',
            'divisions'
        ));
    }

    private function sampleArticles(): array
    {
        $img = fn($n) => asset("images/news-{$n}.jpg");

        return [
            ['slug' => 'metro-rail-new-route', 'title' => 'মেট্রোরেলের নতুন রুট চালু, স্বস্তিতে যাত্রীরা', 'category' => 'রাজধানী', 'category_slug' => 'dhaka', 'excerpt' => 'রাজধানীর গণপরিবহনে নতুন সংযোজন যাত্রীদের দৈনন্দিন যাতায়াতে স্বস্তি আনছে।', 'image_url' => $img(1), 'author' => 'নিজস্ব প্রতিবেদক', 'date' => '১২ মে, ২০২৪'],
            ['slug' => 'student-protest-update', 'title' => 'দাবি আদায়ে শিক্ষার্থীদের আন্দোলন অব্যাহত', 'category' => 'জাতীয়', 'category_slug' => 'national', 'excerpt' => 'শিক্ষার্থীদের আন্দোলন ঘিরে প্রশাসন ও নাগরিক সমাজের নজর এখন রাজধানীসহ বিভিন্ন জেলায়।', 'image_url' => $img(8), 'author' => 'নিজস্ব প্রতিবেদক', 'date' => '০৫ মে, ২০২৪'],
            ['slug' => 'opinion-traffic-jam', 'title' => 'ঢাকার যানজট: সমাধান কোথায়?', 'category' => 'রাজনীতি', 'category_slug' => 'politics', 'excerpt' => 'নগর ব্যবস্থাপনা ও রাজনৈতিক সিদ্ধান্তে যানজট সমাধানের পথ খুঁজছেন বিশেষজ্ঞরা।', 'image_url' => $img(1), 'author' => 'বিশেষ প্রতিনিধি', 'date' => '২৬ এপ্রিল, ২০২৪'],
            ['slug' => 'economic-growth-report', 'title' => 'অর্থনৈতিক প্রবৃদ্ধিতে নতুন রেকর্ড', 'category' => 'অর্থনীতি', 'category_slug' => 'economy', 'excerpt' => 'রপ্তানি আয় ও বিনিয়োগ প্রবাহে ইতিবাচক পরিবর্তনের কথা বলছেন অর্থনীতিবিদেরা।', 'image_url' => $img(4), 'author' => 'বাণিজ্য প্রতিবেদক', 'date' => '০৯ মে, ২০২৪'],
            ['slug' => 'global-market-crisis', 'title' => 'বিশ্ববাজারে অস্থিরতা, প্রভাব পড়ছে অর্থনীতিতে', 'category' => 'শেয়ারবাজার', 'category_slug' => 'stock-market', 'excerpt' => 'বিশ্ববাজারের ওঠানামা দেশের পুঁজিবাজার ও বিনিয়োগকারীদের মনোভাবে প্রভাব ফেলছে।', 'image_url' => $img(4), 'author' => 'অর্থনীতি ডেস্ক', 'date' => '৩০ এপ্রিল, ২০২৪'],
            ['slug' => 'cricket-world-cup-win', 'title' => 'বিশ্বকাপে বাংলাদেশের দুর্দান্ত জয়', 'category' => 'ক্রিকেট', 'category_slug' => 'cricket', 'excerpt' => 'দারুণ ব্যাটিং-বোলিংয়ে বড় জয় তুলে নিয়ে আত্মবিশ্বাসী বাংলাদেশ দল।', 'image_url' => $img(2), 'author' => 'ক্রীড়া প্রতিবেদক', 'date' => '১১ মে, ২০২৪'],
            ['slug' => 'job-circular-government', 'title' => 'সরকারি চাকরির নতুন নিয়োগ বিজ্ঞপ্তি প্রকাশ', 'category' => 'সরকারি চাকরি', 'category_slug' => 'government-jobs', 'excerpt' => 'নতুন নিয়োগে আবেদন প্রক্রিয়া, সময়সীমা ও যোগ্যতার বিস্তারিত জানানো হয়েছে।', 'image_url' => $img(6), 'author' => 'চাকরি ডেস্ক', 'date' => '০৮ মে, ২০২৪'],
            ['slug' => 'health-tips-summer', 'title' => 'গরমে সুস্থ থাকার উপায়', 'category' => 'স্বাস্থ্য', 'category_slug' => 'health', 'excerpt' => 'চিকিৎসকেরা গরমে পানি, খাবার ও দৈনন্দিন অভ্যাসে কিছু সতর্কতা মানার পরামর্শ দিয়েছেন।', 'image_url' => $img(5), 'author' => 'স্বাস্থ্য ডেস্ক', 'date' => '২৭ এপ্রিল, ২০২৪'],
            ['slug' => 'ai-new-development', 'title' => 'কৃত্রিম বুদ্ধিমত্তার নতুন চমক', 'category' => 'তথ্য-প্রযুক্তি', 'category_slug' => 'technology', 'excerpt' => 'নতুন প্রযুক্তি উদ্ভাবন নিয়ে আলোচনা চলছে দেশি-বিদেশি প্রযুক্তি মহলে।', 'image_url' => $img(3), 'author' => 'প্রযুক্তি ডেস্ক', 'date' => '১০ মে, ২০২৪'],
        ];
    }

    public function districts(Request $request): \Illuminate\Http\JsonResponse
    {
        $division = trim($request->input('division', ''));

        if (! $division) {
            return response()->json([]);
        }

        $data = District::forDivision($division);

        return response()->json($data);
    }

    public function upazilas(Request $request): \Illuminate\Http\JsonResponse
    {
        $division = trim($request->input('division', ''));
        $district  = trim($request->input('district', ''));

        if (! $division || ! $district) {
            return response()->json([]);
        }

        $path = resource_path('data/bangladesh-locations.json');
        if (! file_exists($path)) {
            return response()->json([]);
        }
        
        $locationData = json_decode(file_get_contents($path), true) ?? [];
        $upazilas = $locationData[$division]['districts'][$district]['upazilas'] ?? [];

        $bnMapPath = resource_path('data/upazila-name-bn-map.php');
        $bnMap = file_exists($bnMapPath) ? (require $bnMapPath) : [];

        $upazilas = collect($upazilas)
            ->map(function ($upazila) use ($bnMap) {
                if (is_array($upazila)) {
                    $slug = $upazila['slug'] ?? ($upazila['name'] ?? '');
                    $nameBn = $upazila['name_bn'] ?? $this->upazilaLabelBangla($slug, $bnMap);

                    return [
                        'slug' => $slug,
                        'name_bn' => $nameBn,
                    ];
                }

                return [
                    'slug' => $upazila,
                    'name_bn' => $this->upazilaLabelBangla($upazila, $bnMap),
                ];
            })
            ->filter(fn($item) => ! empty($item['slug']))
            ->values()
            ->all();

        return response()->json($upazilas);
    }

    private function upazilaLabelBangla(string $slug, array $bnMap): string
    {
        return $bnMap[$slug] ?? $slug;
    }
}
