<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\SyncVisitorsCommand;
use App\Console\Commands\IngestJobFeedsCommand;
use App\Console\Commands\GenerateSitemapCommand;
use App\Console\Commands\RefreshUserDataCommand;
use App\Console\Commands\PurgeUnconfirmedSubscribers;

Schedule::command(GenerateSitemapCommand::class)
    ->daily()
    ->thenPing(config('services.forge.heatbeats.generate-sitemap'));

Schedule::command(IngestJobFeedsCommand::class)
    ->daily()
    ->at('01:00')
    ->withoutOverlapping()
    ->thenPing(config('services.forge.heatbeats.ingest-job-feeds'));

Schedule::command(RefreshUserDataCommand::class)
    ->hourly()
    ->withoutOverlapping()
    ->thenPing(config('services.forge.heatbeats.refresh-user-data'));

Schedule::command(SyncVisitorsCommand::class)
    ->daily()
    ->thenPing(config('services.forge.heatbeats.sync-visitors'));

Schedule::command(PurgeUnconfirmedSubscribers::class)
    ->daily()
    ->thenPing(config('services.forge.heatbeats.purge-unconfirmed-subscribers'));
