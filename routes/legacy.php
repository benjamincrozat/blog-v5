<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/media-kit', '/advertise', 301);
Route::redirect('/nobinge', 'https://nobinge.ai', 301);
Route::redirect('/deals', '/tools', 301);
Route::redirect('/job-listings', '/jobs', 301);
