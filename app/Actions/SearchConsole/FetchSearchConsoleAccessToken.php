<?php

namespace App\Actions\SearchConsole;

use RuntimeException;
use Illuminate\Http\Client\Factory as HttpFactory;

/**
 * Fetches a Search Console API access token from the configured credentials.
 */
class FetchSearchConsoleAccessToken
{
    protected const string SCOPE = 'https://www.googleapis.com/auth/webmasters';

    public function __construct(
        protected HttpFactory $http,
    ) {}

    public function handle() : SearchConsoleAccessTokenResult
    {
        if ($this->hasServiceAccountCredentials()) {
            return new SearchConsoleAccessTokenResult(
                accessToken: $this->fetchServiceAccountAccessToken(),
                credentialType: 'Service account',
            );
        }

        if ($this->hasRefreshTokenCredentials()) {
            return new SearchConsoleAccessTokenResult(
                accessToken: $this->fetchRefreshTokenAccessToken(),
                credentialType: 'OAuth refresh token',
            );
        }

        throw new RuntimeException(
            'No supported Search Console credentials were configured. ' .
            'Set a service account email/private key or an OAuth client ID/client secret/refresh token.'
        );
    }

    public function hasCredentials() : bool
    {
        return $this->hasServiceAccountCredentials() || $this->hasRefreshTokenCredentials();
    }

    protected function fetchRefreshTokenAccessToken() : string
    {
        return (string) $this->http
            ->asForm()
            ->post($this->tokenUri(), [
                'client_id' => config('services.search_console.oauth.client_id'),
                'client_secret' => config('services.search_console.oauth.client_secret'),
                'grant_type' => 'refresh_token',
                'refresh_token' => config('services.search_console.oauth.refresh_token'),
            ])
            ->throw()
            ->json('access_token');
    }

    protected function fetchServiceAccountAccessToken() : string
    {
        return (string) $this->http
            ->asForm()
            ->post($this->tokenUri(), [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $this->serviceAccountAssertion(),
            ])
            ->throw()
            ->json('access_token');
    }

    protected function serviceAccountAssertion() : string
    {
        $privateKey = str_replace('\n', "\n", (string) config('services.search_console.service_account.private_key'));
        $issuedAt = now()->timestamp;
        $expiresAt = now()->addHour()->timestamp;

        $segments = [
            $this->base64UrlEncode(json_encode([
                'alg' => 'RS256',
                'typ' => 'JWT',
            ], JSON_THROW_ON_ERROR)),
            $this->base64UrlEncode(json_encode([
                'iss' => config('services.search_console.service_account.client_email'),
                'scope' => self::SCOPE,
                'aud' => $this->tokenUri(),
                'iat' => $issuedAt,
                'exp' => $expiresAt,
            ], JSON_THROW_ON_ERROR)),
        ];

        $payload = implode('.', $segments);
        $signature = '';

        if (! openssl_sign($payload, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
            throw new RuntimeException('Unable to sign the Search Console service account assertion.');
        }

        $segments[] = $this->base64UrlEncode($signature);

        return implode('.', $segments);
    }

    protected function hasRefreshTokenCredentials() : bool
    {
        return filled(config('services.search_console.oauth.client_id')) &&
            filled(config('services.search_console.oauth.client_secret')) &&
            filled(config('services.search_console.oauth.refresh_token'));
    }

    protected function hasServiceAccountCredentials() : bool
    {
        return filled(config('services.search_console.service_account.client_email')) &&
            filled(config('services.search_console.service_account.private_key'));
    }

    protected function tokenUri() : string
    {
        return (string) config('services.search_console.token_uri');
    }

    protected function base64UrlEncode(string $value) : string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}
