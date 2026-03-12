<?php

use App\Models\Post;

use function Pest\Laravel\artisan;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\SyncSearchConsoleSitemapCommand;

beforeEach(function () {
    config()->set('app.url', 'https://blog-v5.test');
    config()->set('services.search_console.property', null);
    config()->set('services.search_console.sitemap_url', null);
    config()->set('services.search_console.oauth.client_id', null);
    config()->set('services.search_console.oauth.client_secret', null);
    config()->set('services.search_console.oauth.refresh_token', null);
    config()->set('services.search_console.service_account.client_email', null);
    config()->set('services.search_console.service_account.private_key', null);
});

it('generates the sitemap and submits it with an oauth refresh token', function () {
    Post::factory()->create();

    app()['env'] = 'production';
    config()->set('app.url', 'https://benjamincrozat.com');
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
            '' === $request->body() &&
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

    app()['env'] = 'production';
    config()->set('app.url', 'https://benjamincrozat.com');
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
            '' === $request->body() &&
            'Bearer service-account-token' === $request->header('Authorization')[0];
    });
});

it('prints the full Google error body when sitemap submission fails in production', function () {
    Post::factory()->create();

    app()['env'] = 'production';
    config()->set('app.url', 'https://benjamincrozat.com');
    config()->set('services.search_console.property', 'sc-domain:benjamincrozat.com');
    config()->set('services.search_console.oauth.client_id', 'client-id');
    config()->set('services.search_console.oauth.client_secret', 'client-secret');
    config()->set('services.search_console.oauth.refresh_token', 'refresh-token');

    Http::fake([
        'https://oauth2.googleapis.com/token' => Http::response(['access_token' => 'token'], 200),
        'https://www.googleapis.com/webmasters/v3/sites/*/sitemaps/*' => Http::response([
            'error' => [
                'code' => 400,
                'message' => 'Invalid JSON payload received. Unknown name "". Root element must be a message.',
            ],
        ], 400),
    ]);

    expect(Artisan::call(SyncSearchConsoleSitemapCommand::class))->toBe(1);
    expect(Artisan::output())
        ->toContain('Google Search Console sitemap submission failed with HTTP 400.')
        ->toContain('Invalid JSON payload received.');
});

it('checks the configured Google endpoints outside production without submitting', function () {
    config()->set('services.search_console.property', 'sc-domain:benjamincrozat.com');
    config()->set('services.search_console.oauth.client_id', 'client-id');
    config()->set('services.search_console.oauth.client_secret', 'client-secret');
    config()->set('services.search_console.oauth.refresh_token', 'refresh-token');

    Http::fake([
        'https://oauth2.googleapis.com/token' => Http::sequence()
            ->push('', 405)
            ->push(['access_token' => 'token'], 200),
        'https://www.googleapis.com/webmasters/v3/sites/*/sitemaps/*' => Http::response('', 401),
        'https://www.googleapis.com/webmasters/v3/sites/sc-domain%3Abenjamincrozat.com' => Http::response([
            'siteUrl' => 'sc-domain:benjamincrozat.com',
            'permissionLevel' => 'siteOwner',
        ], 200),
    ]);

    artisan(SyncSearchConsoleSitemapCommand::class)
        ->expectsOutputToContain('Sitemap generated successfully')
        ->expectsTable(
            ['Check', 'Result', 'Details', 'Reference'],
            [
                [
                    'Token endpoint',
                    'HTTP 405',
                    'Google responded on the OAuth endpoint.',
                    'https://oauth2.googleapis.com/token',
                ],
                [
                    'Search Console endpoint',
                    'HTTP 401',
                    'Google responded on the Search Console endpoint.',
                    'https://www.googleapis.com/webmasters/v3/sites/sc-domain%3Abenjamincrozat.com/sitemaps/https%3A%2F%2Fblog-v5.test%2Fsitemap.xml',
                ],
                [
                    'Credentials',
                    'OK',
                    'OAuth refresh token access token acquired successfully.',
                    'OAuth refresh token',
                ],
                [
                    'Property access',
                    'siteOwner',
                    'Configured property is readable with the verified credentials.',
                    'sc-domain:benjamincrozat.com',
                ],
            ],
        )
        ->expectsOutput('Your credentials work, this property is accessible, and this environment is only skipping the final sitemap submission because it is not production.')
        ->expectsOutput('Next step: run this command in production when you want to submit the sitemap for real.')
        ->expectsOutput('Non-production mode does not submit sitemaps. It only checks connectivity and validates credentials read-only.')
        ->expectsOutput('Search Console submission skipped outside production.');

    Http::assertSent(function (Request $request) {
        return 'HEAD' === $request->method() &&
            'https://oauth2.googleapis.com/token' === (string) $request->url();
    });

    Http::assertSent(function (Request $request) {
        return 'HEAD' === $request->method() &&
            str_contains((string) $request->url(), rawurlencode('sc-domain:benjamincrozat.com')) &&
            str_contains((string) $request->url(), rawurlencode('https://blog-v5.test/sitemap.xml'));
    });

    Http::assertSent(function (Request $request) {
        return 'POST' === $request->method() &&
            'https://oauth2.googleapis.com/token' === (string) $request->url() &&
            'refresh_token' === $request['grant_type'];
    });

    Http::assertSent(function (Request $request) {
        return 'GET' === $request->method() &&
            'https://www.googleapis.com/webmasters/v3/sites/sc-domain%3Abenjamincrozat.com' === (string) $request->url() &&
            'Bearer token' === $request->header('Authorization')[0];
    });
});

it('explains how to verify access when credentials are missing', function () {
    config()->set('services.search_console.property', 'sc-domain:benjamincrozat.com');

    Http::fake([
        'https://oauth2.googleapis.com/token' => Http::response('', 404),
        'https://www.googleapis.com/webmasters/v3/sites/*/sitemaps/*' => Http::response('', 404),
    ]);

    artisan(SyncSearchConsoleSitemapCommand::class)
        ->expectsOutputToContain('Sitemap generated successfully')
        ->expectsTable(
            ['Check', 'Result', 'Details', 'Reference'],
            [
                [
                    'Token endpoint',
                    'HTTP 404',
                    'Google responded on the OAuth endpoint.',
                    'https://oauth2.googleapis.com/token',
                ],
                [
                    'Search Console endpoint',
                    'HTTP 404',
                    'Google responded on the Search Console endpoint.',
                    'https://www.googleapis.com/webmasters/v3/sites/sc-domain%3Abenjamincrozat.com/sitemaps/https%3A%2F%2Fblog-v5.test%2Fsitemap.xml',
                ],
                [
                    'Credentials',
                    'Missing',
                    'Add OAuth or service account credentials to verify Search Console access locally.',
                    'Search Console credentials',
                ],
                [
                    'Property access',
                    'Skipped',
                    'Property access was not checked because no credentials are configured.',
                    'sc-domain:benjamincrozat.com',
                ],
            ],
        )
        ->expectsOutput('Google is reachable, but this command cannot verify Search Console yet because no credentials are configured.')
        ->expectsOutput('Fix: add either OAuth credentials or service account credentials to your .env file.')
        ->expectsOutput('Non-production mode does not submit sitemaps. It only checks connectivity and validates credentials read-only.')
        ->expectsOutput('Search Console submission skipped outside production.');
});

it('skips the Search Console submission when credentials or property are not configured in production', function () {
    app()['env'] = 'production';

    Http::fake();

    artisan(SyncSearchConsoleSitemapCommand::class)
        ->expectsOutputToContain('Sitemap generated successfully')
        ->expectsOutput('Search Console submission skipped because credentials or property are not configured.');

    Http::assertNothingSent();
});
