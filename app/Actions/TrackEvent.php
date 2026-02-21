<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

/**
 * Defines the TrackEvent implementation.
 */
class TrackEvent
{
    public function track(string $name, array $meta, string $url, string $ip, string $userAgent, string $acceptLanguage, ?string $referrer = null)
    {
        if (! config('services.pirsch.enabled')) {
            return;
        }

        Http::withToken(config('services.pirsch.access_key'))
            ->retry(3)
            ->post('https://api.pirsch.io/api/v1/event', [
                'event_name' => $name,
                'event_meta' => $meta,
                'url' => $url,
                'ip' => $ip,
                'user_agent' => $userAgent,
                'accept_language' => $acceptLanguage,
                'referrer' => $referrer,
            ])
            ->throw();
    }
}
