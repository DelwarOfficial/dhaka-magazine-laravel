<?php

/* ============================================================
 * Laravel Routes — Dhaka Magazine
 *
 * Add these to: routes/web.php
 * ============================================================ */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ArticleController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{name}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/article/{slug}', [ArticleController::class, 'show'])->name('article.show');
