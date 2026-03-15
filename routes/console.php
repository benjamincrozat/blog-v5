<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\RefreshUserDataCommand;
use App\Console\Commands\SyncSearchConsoleSitemapCommand;

Schedule::command(SyncSearchConsoleSitemapCommand::class)
    ->daily();

Schedule::command(RefreshUserDataCommand::class)
    ->hourly()
    ->withoutOverlapping();
