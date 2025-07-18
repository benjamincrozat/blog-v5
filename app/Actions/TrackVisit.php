<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

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
                'url' => $url,
                'ip' => $ip,
                'user_agent' => $userAgent,
                'accept_language' => $acceptLanguage,
                'referrer' => $referrer,
            ])
            ->throw();
    }
}
