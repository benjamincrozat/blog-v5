<?php

use App\Livewire\Jobs\Index;
use Illuminate\Support\Facades\Route;
use App\Livewire\LinkWizard\LinkWizard;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Jobs\ShowJobController;
use App\Http\Controllers\Posts\ShowPostController;
use App\Http\Controllers\Links\ListLinksController;
use App\Http\Controllers\Posts\ListPostsController;
use App\Http\Controllers\Authors\ShowAuthorController;
use App\Http\Controllers\Checkout\StartCheckoutController;
use App\Http\Controllers\Merchants\ShowMerchantController;
use App\Http\Controllers\Categories\ShowCategoryController;
use App\Http\Controllers\Categories\ListCategoriesController;
use App\Http\Controllers\Checkout\CompletedCheckoutController;
use App\Http\Controllers\Advertising\RedirectToAdvertiserController;
use App\Http\Controllers\Advertising\ShowAdvertisingLandingPageController;

Route::get('/', HomeController::class)
    ->name('home');

Route::get('/blog', ListPostsController::class)
    ->name('posts.index');

Route::get('/authors/{slug}', ShowAuthorController::class)
    ->name('authors.show');

Route::get('/categories', ListCategoriesController::class)
    ->name('categories.index');

Route::get('/categories/{category:slug}', ShowCategoryController::class)
    ->name('categories.show');

Route::get('/links/create', LinkWizard::class)
    ->middleware('auth')
    ->name('links.create');

Route::get('/links', ListLinksController::class)
    ->name('links.index');

Route::view('/tools', 'tools.index')
    ->name('tools.index');

Route::get('/jobs', Index::class)
    ->name('jobs.index');

Route::get('/jobs/{job:slug}', ShowJobController::class)
    ->name('jobs.show');

Route::get('/advertise', ShowAdvertisingLandingPageController::class)
    ->name('advertise');

Route::view('/advertise/guidelines', 'guidelines')
    ->name('advertise.guidelines');

Route::get('/redirect/{slug}', RedirectToAdvertiserController::class)
    ->name('redirect-to-advertiser');

Route::get('/recommends/{slug}', ShowMerchantController::class)
    ->name('merchants.show');

Route::feeds();

Route::get('/checkout/completed', CompletedCheckoutController::class)->name('checkout.completed');
Route::get('/checkout/{product}', StartCheckoutController::class)->name('checkout.start');

// This route needs to be the last one so all others take precedence.
Route::get('/{slug}', ShowPostController::class)
    ->name('posts.show');
