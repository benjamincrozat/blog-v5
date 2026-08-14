<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\GenerateSitemapCommand;
use App\Console\Commands\RefreshUserDataCommand;

Schedule::command(GenerateSitemapCommand::class)
    ->daily();

Schedule::command(RefreshUserDataCommand::class)
    ->hourly()
    ->withoutOverlapping();
