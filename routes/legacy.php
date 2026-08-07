<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/nobinge', 'https://nobinge.ai', 301);
Route::get('/job-listings', fn () => abort(410));
