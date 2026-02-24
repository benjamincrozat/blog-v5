<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Actions\FetchPostSessions;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Refreshes per-post session counts from Pirsch Analytics.
 */
#[AsCommand(
    name: 'app:sync-post-sessions',
    description: 'Fetch fresh numbers about sessions for each post'
)]
class SyncPostSessionsCommand extends Command
{
    public function handle() : void
    {
        app(FetchPostSessions::class)->fetch();

        $this->info('Post sessions data has been fetched and saved.');
    }
}
