<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\LinkWizard\LinkWizard;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Posts\ShowPostController;
use App\Http\Controllers\Links\ListLinksController;
use App\Http\Controllers\Posts\ListPostsController;
use App\Http\Controllers\Merchants\ShowMerchantController;
use App\Http\Controllers\Categories\ShowCategoryController;
use App\Http\Controllers\Posts\ShowPostImagePreviewController;

Route::get('/', HomeController::class)
    ->name('home');

Route::get('/blog', ListPostsController::class)
    ->name('posts.index');

Route::get('/preview/posts/{slug}/image', ShowPostImagePreviewController::class)
    ->name('posts.image-preview');

Route::get('/categories/{category:slug}', ShowCategoryController::class)
    ->name('categories.show');

Route::livewire('/links/create', LinkWizard::class)
    ->middleware('auth')
    ->name('links.create');

Route::get('/links', ListLinksController::class)
    ->name('links.index');

Route::get('/jobs', fn () => abort(410));
Route::get('/jobs/{any}', fn () => abort(410))
    ->where('any', '.*');

Route::get('/newsletter', fn () => abort(410));
Route::get('/subscribers/{subscriber}/confirm', fn () => abort(410));

Route::get('/recommends/{slug}', ShowMerchantController::class)
    ->name('merchants.show');

Route::feeds();

// This route needs to be the last one so all others take precedence.
Route::get('/{slug}', ShowPostController::class)
    ->name('posts.show');
