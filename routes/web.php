<?php

use App\Content\Controllers\ShowPageController;
use App\Http\Controllers\ShowHomepageController;
use App\Http\Controllers\ShowSourcesController;
use App\Http\Controllers\SitemapController;
use App\Tob\Livewire\Calculator;
use App\Tob\Livewire\TickerDatabase;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Sitemap (no rate limiting, cached by search engines)
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

// Public pages with standard rate limiting (60 requests/minute)
Route::middleware(['throttle:60,1'])->group(function () {
    // Homepage
    Route::get('/', ShowHomepageController::class)->name('home');

    // Ticker Database
    Route::get('/tickers', TickerDatabase::class)->name('tickers');

    // Sources page
    Route::get('/bronnen', ShowSourcesController::class)->name('sources');

    // Informational pages (Markdown-driven)
    Route::get('/info/{slug}', ShowPageController::class)
        ->where('slug', '[a-z0-9-]+')
        ->name('page.show');
});

// Calculator with stricter rate limiting (30 requests/minute for file uploads)
Route::middleware(['throttle:30,1'])->group(function () {
    Route::get('/calculator', Calculator::class)->name('calculator');
});
