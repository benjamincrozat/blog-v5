<?php

use App\Models\User;
use Mockery\MockInterface;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\actingAs;

use Illuminate\Support\Facades\Route;

use function Pest\Laravel\withServerVariables;

use App\Actions\TrackVisit as TrackVisitAction;

beforeEach(function () {
    config([
        'app.env' => 'production',
        'services.pirsch.enabled' => true,
    ]);
});

it('tracks visits in production', function () {
    $action = mockTrackVisitAction();

    $action->shouldReceive('track')
        ->once();

    get('/');
});

it('does not track visits in non-production environments', function () {
    $action = mockTrackVisitAction();

    $action->shouldReceive('track')->never();

    config(['app.env' => 'testing']);

    get('/');
});

it('does not track visits when Pirsch is disabled', function () {
    $action = mockTrackVisitAction();

    $action->shouldReceive('track')->never();

    config(['services.pirsch.enabled' => false]);

    get('/');
});

it('only tracks GET requests', function () {
    $action = mockTrackVisitAction();

    $action->shouldReceive('track')->never();

    Route::post('/foo', fn () => '')
        ->middleware(\App\Http\Middleware\TrackVisit::class);

    post('/foo')
        ->assertOk();
});

it('does not track Livewire requests', function () {
    $action = mockTrackVisitAction();

    $action->shouldReceive('track')->never();

    get('/', ['X-Livewire' => 'true']);
});

it('does not track requests that want JSON', function () {
    $action = mockTrackVisitAction();

    $action->shouldReceive('track')->never();

    get('/', ['Accept' => 'application/json']);
});

it('only tracks if all required parameters are available', function () {
    $action = mockTrackVisitAction();

    $action->shouldReceive('track')->never();

    withServerVariables(['REMOTE_ADDR' => null]);

    get('/');
});

it('does not track requests from crawlers', function () {
    $action = mockTrackVisitAction();

    $action->shouldReceive('track')->never();

    get('/', ['User-Agent' => 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; Googlebot/2.1; +http://www.google.com/bot.html) Chrome/W.X.Y.Z Safari/537.36']);
});

it('does not track requests from admins', function () {
    $action = mockTrackVisitAction();

    $action->shouldReceive('track')->never();

    $user = User::factory()->create(['github_login' => 'benjamincrozat']);

    actingAs($user)
        ->get('/');
});

function mockTrackVisitAction() : MockInterface
{
    $action = mock(TrackVisitAction::class);
    app()->instance(TrackVisitAction::class, $action);

    return $action;
}
