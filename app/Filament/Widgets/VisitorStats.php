<?php

namespace App\Filament\Widgets;

use App\Models\Metric;
use Illuminate\Support\Number;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Provides the visitor stats Filament widget.
 */
class VisitorStats extends StatsOverviewWidget
{
    protected ?string $heading = 'Visitors during the last 30 days';

    protected ?string $description = 'Numbers are fetched once a day from the Pirsch Analytics API.';

    protected function getStats() : array
    {
        $visitors = Number::format(
            Metric::query()
                ->where('key', 'visitors')
                ->value('value') ?? 0
        );

        $views = Number::format(
            Metric::query()
                ->where('key', 'views')
                ->value('value') ?? 0
        );

        $sessions = Number::format(
            Metric::query()
                ->where('key', 'sessions')
                ->value('value') ?? 0
        );

        $desktop = Number::format(
            Metric::query()
                ->where('key', 'platform_desktop')
                ->value('value') ?? 0, 0
        );

        return [
            Stat::make('Visitors', $visitors),
            Stat::make('Page views', $views),
            Stat::make('Sessions', $sessions),
            Stat::make('Desktop', "$desktop%"),
        ];
    }
}
