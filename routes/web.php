<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\LinkWizard\LinkWizard;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Posts\ShowPostController;
use App\Http\Controllers\Links\ListLinksController;
use App\Http\Controllers\Posts\ListPostsController;
use App\Http\Controllers\Merchants\ShowMerchantController;
use App\Http\Controllers\Categories\ShowCategoryController;
use App\Http\Controllers\Categories\ListCategoriesController;

Route::get('/', HomeController::class)
    ->name('home');

Route::get('/blog', ListPostsController::class)
    ->name('posts.index');

Route::get('/categories', ListCategoriesController::class)
    ->name('categories.index');

Route::get('/categories/{category:slug}', ShowCategoryController::class)
    ->name('categories.show');

Route::get('/links', ListLinksController::class)
    ->name('links.index');

Route::get('/links/create', LinkWizard::class)
    ->name('links.create');

Route::get('/recommends/{slug}', ShowMerchantController::class)
    ->name('merchants.show');

Route::feeds();

// This route needs to be the last one so all others take precedence.
Route::get('/{post:slug}', ShowPostController::class)
    ->name('posts.show');
