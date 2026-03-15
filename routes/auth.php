<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Posts\ShowPostMarkdownController;
use App\Http\Controllers\Auth\GithubAuthCallbackController;
use App\Http\Controllers\Auth\GithubAuthRedirectController;
use App\Http\Controllers\Impersonation\LeaveImpersonationController;

Route::middleware('guest')
    ->group(function () {
        Route::view('/login', 'login')
            ->name('login');

        Route::get('/auth/redirect', GithubAuthRedirectController::class)
            ->name('auth.redirect');

        Route::get('/auth/callback', GithubAuthCallbackController::class)
            ->name('auth.callback');
    });

Route::middleware('auth')
    ->group(function () {
        Route::post('/logout', LogoutController::class)
            ->middleware('auth')
            ->name('logout');

        Route::get('/admin/posts/{post}/markdown', ShowPostMarkdownController::class)
            ->name('admin.posts.markdown');

        Route::get('/leave-impersonation', LeaveImpersonationController::class)
            ->middleware('auth')
            ->name('leave-impersonation');
    });
