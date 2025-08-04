<?php

use App\Livewire\Quiz;
use Illuminate\Support\Facades\Route;
use App\Livewire\LinkWizard\LinkWizard;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Posts\ShowPostController;
use App\Http\Controllers\Links\ListLinksController;
use App\Http\Controllers\Posts\ListPostsController;
use App\Http\Controllers\Authors\ShowAuthorController;
use App\Http\Controllers\Merchants\ShowMerchantController;
use App\Http\Controllers\Categories\ShowCategoryController;
use App\Http\Controllers\Categories\ListCategoriesController;
use App\Http\Controllers\Advertising\RedirectToAdvertiserController;

Route::get('/', HomeController::class)
    ->name('home');

Route::get('/blog', ListPostsController::class)
    ->name('posts.index');

Route::get('/quizzes/{quiz:slug}', Quiz::class)
    ->name('quizzes.show');

Route::get('/authors/{user:slug}', ShowAuthorController::class)
    ->name('authors.show');

Route::get('/categories', ListCategoriesController::class)
    ->name('categories.index');

Route::get('/categories/{category:slug}', ShowCategoryController::class)
    ->name('categories.show');

Route::view('/deals', 'deals')
    ->name('deals');

Route::get('/links/create', LinkWizard::class)
    ->middleware('auth')
    ->name('links.create');

Route::get('/links', ListLinksController::class)
    ->name('links.index');

Route::get('/advertise', App\Http\Controllers\Advertising\ShowAdvertisingLandingPageController::class)
    ->name('advertise');

Route::get('/redirect/{slug}', RedirectToAdvertiserController::class)
    ->name('redirect-to-advertiser');

Route::get('/recommends/{slug}', ShowMerchantController::class)
    ->name('merchants.show');

Route::feeds();

// This route needs to be the last one so all others take precedence.
Route::get('/{slug}', ShowPostController::class)
    ->name('posts.show');
