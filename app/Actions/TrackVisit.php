<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

/**
 * Track visits by sending a small analytics payload to Pirsch.
 *
 * This exists because we want visit tracking to happen after the response is sent
 * (via middleware termination) while keeping HTTP and payload concerns out of middleware.
 */
class TrackVisit
{
    /**
     * Track a visit to a given URL.
     */
    public function track(string $url, string $ip, string $userAgent, string $acceptLanguage, ?string $referrer = null)
    {
        Http::withToken(config('services.pirsch.access_key'))
            ->retry(3)
            ->post('https://api.pirsch.io/api/v1/hit', [
                'url' => $this->ensureUtf8($url),
                'ip' => $ip,
                'user_agent' => $this->ensureUtf8($userAgent),
                'accept_language' => $this->ensureUtf8($acceptLanguage),
                'referrer' => null !== $referrer ? $this->ensureUtf8($referrer) : null,
            ])
            ->throw();
    }

    /**
     * Ensure a string is valid UTF-8 so Guzzle can JSON-encode the payload.
     *
     * Incoming request data can include malformed byte sequences (e.g. from legacy browsers
     * or badly encoded URLs/headers). Pirsch expects JSON, so we must guarantee valid UTF-8.
     */
    protected function ensureUtf8(string $value) : string
    {
        if ('' === $value || mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }

        return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
    }
}
