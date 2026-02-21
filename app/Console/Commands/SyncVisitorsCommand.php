<?php

namespace App\Console\Commands;

use App\Models\Metric;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Defines the SyncVisitorsCommand implementation.
 */
// This command uses Pirsch Analytics' API (https://benjamincrozat.com/recommends/pirsch-analytics) to fetch fresh numbers about my visitors.
#[AsCommand(
    name: 'app:sync-visitors',
    description: 'Fetch fresh numbers about visitors from the analytics provider'
)]
class SyncVisitorsCommand extends Command
{
    public function handle() : void
    {
        $this->info('Fetching fresh analytics data…');

        $data = $this->fetch();

        Metric::query()->create([
            'key' => 'platform_desktop',
            'value' => ($data['relative_platform_desktop'] ?? 0) * 100,
        ]);

        Metric::query()->create([
            'key' => 'sessions',
            'value' => $data['sessions'] ?? 0,
        ]);

        Metric::query()->create([
            'key' => 'views',
            'value' => $data['views'] ?? 0,
        ]);

        Metric::query()->create([
            'key' => 'visitors',
            'value' => $data['visitors'] ?? 0,
        ]);

        $this->info('Fresh analytics data has been fetched.');
    }

    protected function fetch() : array
    {
        $accessToken = Http::post('https://api.pirsch.io/api/v1/token', [
            'client_id' => config('services.pirsch.client_id'),
            'client_secret' => config('services.pirsch.client_secret'),
        ])
            ->throw()
            ->json('access_token');

        $overview = Http::withToken($accessToken)
            ->get('https://api.pirsch.io/api/v1/statistics/total', [
                'id' => config('services.pirsch.domain_id'),
                'from' => now()->subDays(31)->toDateString(),
                'to' => now()->subDay()->toDateString(),
                'timezone' => 'Europe/Paris',
            ])
            ->throw()
            ->json();

        $platform = Http::withToken($accessToken)
            ->get('https://api.pirsch.io/api/v1/statistics/platform', [
                'id' => config('services.pirsch.domain_id'),
                'from' => now()->subDays(31)->toDateString(),
                'to' => now()->subDay()->toDateString(),
                'timezone' => 'Europe/Paris',
            ])
            ->throw()
            ->json();

        return array_merge($overview, $platform);
    }
}
