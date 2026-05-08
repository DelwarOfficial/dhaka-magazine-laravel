<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ArticleController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api/photo-story', [HomeController::class, 'photoStoryData'])->name('photo-story.data');
Route::get('/category/{parentSlug}', [CategoryController::class, 'showParent'])->name('category.parent');
Route::get('/category/{parentSlug}/{childSlug}', [CategoryController::class, 'showChild'])->name('category.child');
Route::get('/sitemap.xml', [CategoryController::class, 'sitemap'])->name('sitemap');
Route::get('/article/{slug}', [ArticleController::class, 'show'])->name('article.show');
