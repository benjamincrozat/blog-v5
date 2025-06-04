<?php

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\withServerVariables;

use Facades\App\Actions\TrackVisit;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    config(['app.env' => 'production']);
});

it('tracks visits in production', function () {
    TrackVisit::expects('track');

    get('/');
});

it('does not track visits in non-production environments', function () {
    TrackVisit::shouldReceive('track')->never();

    config(['app.env' => 'testing']);

    get('/');
});

it('only tracks GET requests', function () {
    TrackVisit::shouldReceive('track')->never();

    Route::post('/foo', fn () => '')
        ->middleware(\App\Http\Middleware\TrackVisit::class);

    post('/foo')
        ->assertOk();
});

it('does not track Livewire requests', function () {
    TrackVisit::shouldReceive('track')->never();

    get('/', ['X-Livewire' => 'true']);
});

it('does not track requests that want JSON', function () {
    TrackVisit::shouldReceive('track')->never();

    get('/', ['Accept' => 'application/json']);
});

it('only tracks if all required parameters are available', function () {
    TrackVisit::shouldReceive('track')->never();

    withServerVariables(['REMOTE_ADDR' => null]);

    get('/');
});
