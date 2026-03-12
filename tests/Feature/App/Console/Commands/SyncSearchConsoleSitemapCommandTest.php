<?php

use App\Models\Post;

use function Pest\Laravel\artisan;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use App\Console\Commands\SyncSearchConsoleSitemapCommand;

it('generates the sitemap and submits it with an oauth refresh token', function () {
    Post::factory()->create();

    config()->set('app.url', 'https://benjamincrozat.com');
    config()->set('services.search_console.enabled', true);
    config()->set('services.search_console.property', 'sc-domain:benjamincrozat.com');
    config()->set('services.search_console.oauth.client_id', 'client-id');
    config()->set('services.search_console.oauth.client_secret', 'client-secret');
    config()->set('services.search_console.oauth.refresh_token', 'refresh-token');

    Http::fake([
        'https://oauth2.googleapis.com/token' => Http::response(['access_token' => 'token'], 200),
        'https://www.googleapis.com/webmasters/v3/sites/*/sitemaps/*' => Http::response('', 204),
    ]);

    artisan(SyncSearchConsoleSitemapCommand::class)
        ->expectsOutputToContain('Sitemap generated successfully')
        ->expectsOutput('Sitemap submitted to Google Search Console.');

    expect(File::exists(public_path('sitemap.xml')))->toBeTrue();

    Http::assertSent(function (Request $request) {
        return 'https://oauth2.googleapis.com/token' === (string) $request->url() &&
            'client-id' === $request['client_id'] &&
            'client-secret' === $request['client_secret'] &&
            'refresh-token' === $request['refresh_token'] &&
            'refresh_token' === $request['grant_type'];
    });

    Http::assertSent(function (Request $request) {
        return 'PUT' === $request->method() &&
            str_contains((string) $request->url(), rawurlencode('sc-domain:benjamincrozat.com')) &&
            str_contains((string) $request->url(), rawurlencode('https://benjamincrozat.com/sitemap.xml')) &&
            'Bearer token' === $request->header('Authorization')[0];
    });
});

it('generates the sitemap and submits it with service account credentials', function () {
    Post::factory()->create();

    $privateKey = openssl_pkey_new([
        'private_key_bits' => 2048,
        'private_key_type' => OPENSSL_KEYTYPE_RSA,
    ]);

    expect($privateKey)->not->toBeFalse();

    openssl_pkey_export($privateKey, $exportedPrivateKey);

    config()->set('app.url', 'https://benjamincrozat.com');
    config()->set('services.search_console.enabled', true);
    config()->set('services.search_console.property', 'https://benjamincrozat.com/');
    config()->set('services.search_console.service_account.client_email', 'search-console@benjamincrozat.com');
    config()->set('services.search_console.service_account.private_key', str_replace("\n", '\n', $exportedPrivateKey));

    Http::fake([
        'https://oauth2.googleapis.com/token' => Http::response(['access_token' => 'service-account-token'], 200),
        'https://www.googleapis.com/webmasters/v3/sites/*/sitemaps/*' => Http::response('', 204),
    ]);

    artisan(SyncSearchConsoleSitemapCommand::class)
        ->expectsOutputToContain('Sitemap generated successfully')
        ->expectsOutput('Sitemap submitted to Google Search Console.');

    Http::assertSent(function (Request $request) {
        return 'https://oauth2.googleapis.com/token' === (string) $request->url() &&
            'urn:ietf:params:oauth:grant-type:jwt-bearer' === $request['grant_type'] &&
            filled($request['assertion']);
    });

    Http::assertSent(function (Request $request) {
        return 'PUT' === $request->method() &&
            str_contains((string) $request->url(), rawurlencode('https://benjamincrozat.com/')) &&
            str_contains((string) $request->url(), rawurlencode('https://benjamincrozat.com/sitemap.xml')) &&
            'Bearer service-account-token' === $request->header('Authorization')[0];
    });
});

it('skips the Search Console submission when it is disabled', function () {
    Http::fake();

    artisan(SyncSearchConsoleSitemapCommand::class)
        ->expectsOutputToContain('Sitemap generated successfully')
        ->expectsOutput('Search Console submission skipped because it is disabled.');

    Http::assertNothingSent();
});
