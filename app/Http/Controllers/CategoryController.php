<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display articles for a given category.
     */
    public function show($name)
    {
        // Replace with Eloquent:
        // $categoryArticles = Article::where('category', urldecode($name))->get()->toArray();

        $categoryName = urldecode($name);

        $categoryArticles = [
            // Example article array structure expected by category.blade.php:
            // [
            //     'slug'      => 'article-slug',
            //     'title'     => 'Article Title',
            //     'category'  => 'জাতীয়',
            //     'excerpt'   => 'Short description...',
            //     'image_url' => asset('images/news-1.jpg'),
            //     'author'    => 'Author Name',
            //     'date'      => '১২ মে, ২০২৪',
            // ],
        ];

        $popularNews = [
            // Same structure as sidebar expects
        ];

        return view('pages.category', compact(
            'categoryName',
            'categoryArticles',
            'popularNews',
        ));
    }
}
