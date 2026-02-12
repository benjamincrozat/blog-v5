<?php

use App\Actions\TrackEvent;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

it('posts event to Pirsch with retries and expected payload', function () {
    config([
        'services.pirsch.enabled' => true,
        'services.pirsch.access_key' => 'pirsch-test-key',
    ]);

    Http::fake([
        'api.pirsch.io/api/v1/event' => Http::sequence()
            ->pushStatus(500)
            ->pushStatus(500)
            ->push([], 200),
    ]);

    $action = new TrackEvent;

    $action->track(
        'signup',
        ['plan' => 'pro'],
        'https://example.com/pricing',
        '203.0.113.10',
        'UA',
        'en-US',
        'https://google.com'
    );

    Http::assertSentCount(3);
    Http::assertSent(function (Illuminate\Http\Client\Request $request) {
        return $request->hasHeader('Authorization', 'Bearer pirsch-test-key')
            && 'https://api.pirsch.io/api/v1/event' === $request->url()
            && 'POST' === $request->method()
            && 'signup' === $request['event_name']
            && $request['event_meta'] === ['plan' => 'pro']
            && 'https://example.com/pricing' === $request['url']
            && '203.0.113.10' === $request['ip']
            && 'UA' === $request['user_agent']
            && 'en-US' === $request['accept_language']
            && 'https://google.com' === $request['referrer'];
    });
});

it('throws when Pirsch returns errors after retries', function () {
    config([
        'services.pirsch.enabled' => true,
        'services.pirsch.access_key' => 'pirsch-test-key',
    ]);

    Http::fake([
        'api.pirsch.io/api/v1/event' => Http::sequence()
            ->pushStatus(500)
            ->pushStatus(500)
            ->pushStatus(500),
    ]);

    $action = new TrackEvent;

    expect(fn () => $action->track('name', [], 'https://example.com', '1.1.1.1', 'UA', 'en'))
        ->toThrow(RequestException::class);
});

beforeEach(fn () => Http::allowStrayRequests());

it("successfully makes a call to Pirsch's API with valid parameters", function () {
    config(['services.pirsch.enabled' => true]);
    Http::fake(['api.pirsch.io/api/v1/event' => Http::response([], 200)]);

    app(TrackEvent::class)->track(...trackEventParameters());
})->throwsNoExceptions();

it('handles an invalid token appropriately', function () {
    config([
        'services.pirsch.enabled' => true,
        'services.pirsch.access_key' => 'invalid_token',
    ]);
    Http::fake(['api.pirsch.io/api/v1/event' => Http::response([], 401)]);

    app(TrackEvent::class)->track(...trackEventParameters());
})->throws(RequestException::class);

it('retries on network failure and does not throw if it succeeds', function () {
    config(['services.pirsch.enabled' => true]);

    Http::fakeSequence('api.pirsch.io/api/v1/event')
        ->pushStatus(503)
        ->pushStatus(503)
        ->pushStatus(200);

    app(TrackEvent::class)->track(...trackEventParameters());
})->throwsNoExceptions();

it('properly handles request timeouts', function () {
    config(['services.pirsch.enabled' => true]);

    Http::fakeSequence('api.pirsch.io/api/v1/event')
        ->pushStatus(408)
        ->pushStatus(408)
        ->pushStatus(200);

    app(TrackEvent::class)->track(...trackEventParameters());
})->throwsNoExceptions();

function trackEventParameters() : array
{
    return [
        'name' => 'Foo',
        'meta' => ['foo' => 'bar'],
        'url' => fake()->url(),
        'ip' => fake()->ipv4(),
        'userAgent' => fake()->userAgent(),
        'acceptLanguage' => fake()->languageCode(),
        'referrer' => fake()->url(),
    ];
}
