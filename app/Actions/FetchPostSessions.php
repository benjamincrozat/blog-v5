<?php

namespace App\Actions;

use App\Models\Post;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

/**
 * Syncs Pirsch session counts into posts.
 *
 * Extracted to keep analytics integration isolated from commands and jobs.
 * Callers can rely on sessions being aggregated by slug and stored on posts.
 */
class FetchPostSessions
{
    /**
     * Fetch the number of sessions for each post from Pirsch.
     */
    public function fetch(?CarbonImmutable $from = null, ?CarbonImmutable $to = null) : void
    {
        $pirschAccessToken = Http::post('https://api.pirsch.io/api/v1/token', [
            'client_id' => config('services.pirsch.client_id'),
            'client_secret' => config('services.pirsch.client_secret'),
        ])
            ->throw()
            ->json('access_token');

        $from ??= now()->subDays(7);

        $to ??= now();

        Http::withToken($pirschAccessToken)
            ->get('https://api.pirsch.io/api/v1/statistics/page', [
                'id' => config('services.pirsch.domain_id'),
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
                'tz' => 'UTC',
            ])
            ->throw()
            ->collect()
            ->map(function (array $item) {
                $item['path'] = explode('#', $item['path'])[0];

                return $item;
            })
            ->groupBy('path')
            ->each(function (Collection $items) {
                Post::query()
                    ->where('slug', trim($items[0]['path'], '/'))
                    ->update(['sessions_count' => $items->sum('sessions')]);
            });
    }
}
