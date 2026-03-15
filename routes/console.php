<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\RefreshUserDataCommand;
use App\Console\Commands\SyncSearchConsoleSitemapCommand;

$generateSitemapHeartbeat = config('services.forge.heartbeats.generate_sitemap');
$refreshUserDataHeartbeat = config('services.forge.heartbeats.refresh_user_data');

Schedule::command(SyncSearchConsoleSitemapCommand::class)
    ->daily()
    ->thenPingIf(filled($generateSitemapHeartbeat), $generateSitemapHeartbeat);

Schedule::command(RefreshUserDataCommand::class)
    ->hourly()
    ->withoutOverlapping()
    ->thenPingIf(filled($refreshUserDataHeartbeat), $refreshUserDataHeartbeat);
