<?php

use App\Models\Post;
use App\Actions\FetchPostSessions;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

beforeEach(fn () => Http::allowStrayRequests());

it("successfully fetches sessions from Pirsch's API and updates the related posts", function () {
    $first = Post::factory()->create([
        'slug' => 'foo',
        'sessions_count' => 0,
    ]);

    $second = Post::factory()->create([
        'slug' => 'bar',
        'sessions_count' => 0,
    ]);

    Http::fake([
        'api.pirsch.io/api/v1/token' => Http::response(['access_token' => 'token'], 200),
        'api.pirsch.io/api/v1/statistics/page*' => Http::response([
            ['path' => '/foo', 'sessions' => 10],
            ['path' => '/foo#section', 'sessions' => 5],
            ['path' => '/bar', 'sessions' => 20],
            ['path' => '/bar#comment', 'sessions' => 4],
        ], 200),
    ]);

    app(FetchPostSessions::class)->fetch();

    expect($first->refresh()->sessions_count)->toBe(15)
        ->and($second->refresh()->sessions_count)->toBe(24);
});

it('throws when Pirsch refuses credentials', function () {
    config([
        'services.pirsch.client_id' => 'wrong_id',
        'services.pirsch.client_secret' => 'wrong_secret',
    ]);

    Http::fake([
        'api.pirsch.io/api/v1/token' => Http::response(['message' => 'Unauthorized'], 401),
    ]);

    app(FetchPostSessions::class)->fetch();
})->throws(RequestException::class);

it('sends the provided date range to Pirsch', function () {
    $from = now()->subDays(14)->startOfDay()->toImmutable();
    $to = now()->subDays(7)->endOfDay()->toImmutable();

    Http::fake([
        'api.pirsch.io/api/v1/token' => Http::response(['access_token' => 'token'], 200),
        'api.pirsch.io/api/v1/statistics/page*' => Http::response([], 200),
    ]);

    app(FetchPostSessions::class)->fetch($from, $to);

    Http::assertSent(function (Request $request) use ($from, $to) {
        if (! str_contains($request->url(), '/statistics/page')) {
            return false;
        }

        parse_str(parse_url($request->url(), PHP_URL_QUERY), $query);

        expect($query['from'] ?? null)->toBe($from->toDateString());
        expect($query['to'] ?? null)->toBe($to->toDateString());

        return true;
    });
});
