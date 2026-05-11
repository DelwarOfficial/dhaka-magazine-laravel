<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\ArticleFeed;
use App\Support\FallbackDataService;

class ArticleController extends Controller
{
    public function show(string $slug)
    {
        $fallbackArticles = FallbackDataService::getArticles();
        $articles = ArticleFeed::allForRelated($fallbackArticles);
        $article = ArticleFeed::findArticle($slug, $fallbackArticles);

        if (!$article) {
            abort(404);
        }

        // Related: same category, exclude current, take 3
        $relatedArticles = collect($articles)
            ->filter(fn($a) => $a['category'] === $article['category'] && $a['slug'] !== $article['slug'])
            ->take(3)
            ->values()
            ->toArray();

        // Fill up to 3 from other articles if needed
        if (count($relatedArticles) < 3) {
            $remaining = collect($articles)
                ->filter(fn($a) => $a['slug'] !== $article['slug'] && !in_array($a['slug'], array_column($relatedArticles, 'slug')))
                ->take(3 - count($relatedArticles))
                ->values()
                ->toArray();
            $relatedArticles = array_merge($relatedArticles, $remaining);
        }

        $popularNews = array_slice($articles, 5, 5);

        return view('pages.article', compact(
            'article',
            'relatedArticles',
            'popularNews',
        ));
    }
}
