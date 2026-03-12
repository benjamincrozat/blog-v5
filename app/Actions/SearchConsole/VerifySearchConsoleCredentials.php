<?php

namespace App\Actions\SearchConsole;

use Throwable;
use Illuminate\Http\Client\Factory as HttpFactory;

/**
 * Verifies the configured credentials and property access without submitting a sitemap.
 */
class VerifySearchConsoleCredentials
{
    public function __construct(
        protected HttpFactory $http,
        protected FetchSearchConsoleAccessToken $fetchSearchConsoleAccessToken,
    ) {}

    /**
     * @return array<int, array{check: string, result: string, details: string, reference: string}>
     */
    public function handle() : array
    {
        if (! $this->fetchSearchConsoleAccessToken->hasCredentials()) {
            return [
                [
                    'check' => 'Credentials',
                    'result' => 'Missing',
                    'details' => 'Add OAuth or service account credentials to verify Search Console access locally.',
                    'reference' => 'Search Console credentials',
                ],
                [
                    'check' => 'Property access',
                    'result' => 'Skipped',
                    'details' => 'Property access was not checked because no credentials are configured.',
                    'reference' => (string) (config('services.search_console.property') ?: 'SEARCH_CONSOLE_PROPERTY'),
                ],
            ];
        }

        try {
            $accessToken = $this->fetchSearchConsoleAccessToken->handle();
        } catch (Throwable $exception) {
            return [
                [
                    'check' => 'Credentials',
                    'result' => 'Failed',
                    'details' => $exception->getMessage(),
                    'reference' => $this->credentialReference(),
                ],
                [
                    'check' => 'Property access',
                    'result' => 'Skipped',
                    'details' => 'Property access was not checked because credentials could not be verified.',
                    'reference' => (string) (config('services.search_console.property') ?: 'SEARCH_CONSOLE_PROPERTY'),
                ],
            ];
        }

        $rows = [[
            'check' => 'Credentials',
            'result' => 'OK',
            'details' => "{$accessToken->credentialType} access token acquired successfully.",
            'reference' => $accessToken->credentialType,
        ]];

        $property = (string) config('services.search_console.property');

        if ('' === $property) {
            $rows[] = [
                'check' => 'Property access',
                'result' => 'Skipped',
                'details' => 'Set SEARCH_CONSOLE_PROPERTY to verify property access.',
                'reference' => 'SEARCH_CONSOLE_PROPERTY',
            ];

            return $rows;
        }

        $response = $this->http
            ->withToken($accessToken->accessToken)
            ->get('https://www.googleapis.com/webmasters/v3/sites/' . rawurlencode($property));

        if ($response->successful()) {
            $rows[] = [
                'check' => 'Property access',
                'result' => (string) ($response->json('permissionLevel') ?: 'OK'),
                'details' => 'Configured property is readable with the verified credentials.',
                'reference' => (string) ($response->json('siteUrl') ?: $property),
            ];

            return $rows;
        }

        $rows[] = [
            'check' => 'Property access',
            'result' => 'HTTP ' . $response->status(),
            'details' => (string) ($response->json('error.message') ?: 'Google rejected the property access check.'),
            'reference' => $property,
        ];

        return $rows;
    }

    protected function credentialReference() : string
    {
        if (filled(config('services.search_console.service_account.client_email'))) {
            return (string) config('services.search_console.service_account.client_email');
        }

        if (filled(config('services.search_console.oauth.client_id'))) {
            return 'OAuth client ID';
        }

        return 'Search Console credentials';
    }
}
