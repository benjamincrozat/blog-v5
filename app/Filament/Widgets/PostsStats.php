<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\Metric;
use Illuminate\Support\Number;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Provides the posts stats Filament widget.
 */
class PostsStats extends StatsOverviewWidget
{
    protected ?string $heading = 'Posts stats';

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
                ->value('value') ?? 0,
            0
        );

        return [
            Stat::make('Published posts', Post::query()->published()->count()),
            Stat::make('Informational posts', Post::query()->published()->where('is_commercial', false)->count()),
            Stat::make('Commercial posts', Post::query()->published()->where('is_commercial', true)->count()),
        ];
    }
}
