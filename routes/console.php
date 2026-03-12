<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\SyncVisitorsCommand;
use App\Console\Commands\RefreshUserDataCommand;
use App\Console\Commands\SyncSearchConsoleSitemapCommand;

Schedule::command(SyncSearchConsoleSitemapCommand::class)
    ->daily()
    ->thenPing(config('services.forge.heatbeats.generate-sitemap'));

Schedule::command(RefreshUserDataCommand::class)
    ->hourly()
    ->withoutOverlapping()
    ->thenPing(config('services.forge.heatbeats.refresh-user-data'));

Schedule::command(SyncVisitorsCommand::class)
    ->daily()
    ->thenPing(config('services.forge.heatbeats.sync-visitors'));
