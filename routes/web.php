<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/latest', [NewsController::class, 'latest'])->name('news.latest');
Route::get('/api/photo-story', [HomeController::class, 'photoStoryData'])->name('photo-story.data');
Route::get('/category/{parentSlug}', [CategoryController::class, 'showParent'])->name('category.parent');
Route::get('/category/{parentSlug}/{childSlug}', [CategoryController::class, 'showChild'])->name('category.child');
Route::get('/sitemap.xml', [CategoryController::class, 'sitemap'])->name('sitemap');
foreach (['article', 'video', 'live', 'gallery', 'opinion'] as $fmt) {
    Route::get("/{$fmt}/{slug}", [ArticleController::class, 'show'])->name("{$fmt}.show");
}

// JSON endpoints for dependent local-news dropdowns.
Route::get('/api/saradesh/districts', [CategoryController::class, 'districts'])->name('saradesh.districts');
Route::get('/api/saradesh/upazilas', [CategoryController::class, 'upazilas'])->name('saradesh.upazilas');
