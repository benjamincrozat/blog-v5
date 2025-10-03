<?php

namespace App\Actions;

use App\Models\Post;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Factory;
use Illuminate\Container\Attributes\Config;

class FetchPostSessions
{
    public function __construct(
        protected Factory $http,
        #[Config('services.pirsch.client_id')]
        protected string $pirschClientId,
        #[Config('services.pirsch.client_secret')]
        protected string $pirschClientSecret,
    ) {}

    /**
     * Fetch the number of sessions for each post from Pirsch.
     */
    public function fetch(?CarbonImmutable $from = null, ?CarbonImmutable $to = null) : void
    {
        $shouldAuthenticate = filled($this->pirschClientId) && filled($this->pirschClientSecret);

        $pirschAccessToken = null;

        if ($shouldAuthenticate) {
            $pirschAccessToken = $this->http
                ->post('https://api.pirsch.io/api/v1/token', [
                    'client_id' => $this->pirschClientId,
                    'client_secret' => $this->pirschClientSecret,
                ])
                ->throw()
                ->json('access_token');
        }

        $from ??= now()->subDays(7);

        $to ??= now();

        $request = $this->http;

        if ($shouldAuthenticate && filled($pirschAccessToken)) {
            $request = $request->withToken($pirschAccessToken);
        }

        $request
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
