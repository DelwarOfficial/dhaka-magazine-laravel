<?php

namespace App\Http\Controllers;

use App\Support\ArticleFeed;
use App\Support\FallbackDataService;
use App\Services\PopularNewsService;
use App\Services\RelatedArticleService;

class ArticleController extends Controller
{
    public function __construct(
        private readonly PopularNewsService $popularNews,
        private readonly RelatedArticleService $relatedArticles,
    ) {
    }

    public function show(string $slug)
    {
        $fallbackArticles = FallbackDataService::getArticles();
        $article = ArticleFeed::findArticle($slug, $fallbackArticles);

        if (!$article) {
            abort(404);
        }

        $relatedArticles = $this->relatedArticles->forArticle($article);
        $popularNews = $this->popularNews->get(5, array_filter([(int) ($article['id'] ?? 0)]));

        return view('pages.article', compact(
            'article',
            'relatedArticles',
            'popularNews',
        ));
    }
}
