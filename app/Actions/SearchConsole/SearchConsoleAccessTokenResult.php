<?php

namespace App\Actions\SearchConsole;

/**
 * Carries the access token details fetched for Search Console API calls.
 */
class SearchConsoleAccessTokenResult
{
    public function __construct(
        public readonly string $accessToken,
        public readonly string $credentialType,
    ) {}
}
