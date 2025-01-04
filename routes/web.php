<?php

use App\Http\Middleware\Jetstream;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShowPostController;
use App\Http\Controllers\ListPostsController;

Route::get('/', HomeController::class)->name('home');

Route::get('/blog', ListPostsController::class)->name('posts.index');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    Jetstream::class,
])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
});

Route::get('/{slug}', ShowPostController::class)->name('posts.show');
