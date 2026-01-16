<?php

use App\Actions\TrackVisit;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

beforeEach(fn () => Http::allowStrayRequests());

it("successfully makes a call to Pirsch's API with valid parameters", function () {
    app(TrackVisit::class)->track(...trackVisitParameters());
})->throwsNoExceptions();

it('sanitizes malformed UTF-8 so the payload can be JSON encoded', function () {
    Http::fake(function (Request $request) {
        $data = $request->data();

        expect(mb_check_encoding($data['url'], 'UTF-8'))->toBeTrue()
            ->and(mb_check_encoding($data['user_agent'], 'UTF-8'))->toBeTrue()
            ->and(mb_check_encoding($data['accept_language'], 'UTF-8'))->toBeTrue();

        return Http::response('', 200);
    });

    $invalidUtf8 = "https://example.com/\xC3\x28";

    app(TrackVisit::class)->track(
        $invalidUtf8,
        fake()->ipv4(),
        "Mozilla/5.0\xC3\x28",
        "en-US\xC3\x28",
        null,
    );
});

it('handles an invalid token appropriately', function () {
    config(['services.pirsch.access_key' => 'invalid_token']);

    app(TrackVisit::class)->track(...trackVisitParameters());
})->throws(RequestException::class);

it('retries on network failure and does not throw if it succeeds', function () {
    Http::fakeSequence('api.pirsch.io/api/v1/hit')
        ->pushStatus(503)
        ->pushStatus(503)
        ->pushStatus(200);

    app(TrackVisit::class)->track(...trackVisitParameters());
})->throwsNoExceptions();

it('properly handles request timeouts', function () {
    Http::fakeSequence()
        ->pushStatus(408)
        ->pushStatus(408)
        ->pushStatus(200);

    app(TrackVisit::class)->track(...trackVisitParameters());
})->throwsNoExceptions();

function trackVisitParameters() : array
{
    return [
        'url' => fake()->url(),
        'ip' => fake()->ipv4(),
        'userAgent' => fake()->userAgent(),
        'acceptLanguage' => fake()->languageCode(),
        'referrer' => fake()->url(),
    ];
}
