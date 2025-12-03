<?php

namespace App\Console\Commands;

use App\Models\Subscriber;
use Illuminate\Console\Command;

class PurgeUnconfirmedSubscribers extends Command
{
    protected $signature = 'subscribers:purge-unconfirmed {--days=30 : The number of days to wait before purging.}';

    protected $description = 'Delete subscribers who never confirmed their email address within the given timeframe.';

    public function handle() : int
    {
        $days = (int) $this->option('days');

        $cutoff = now()->subDays($days);

        $deleted = Subscriber::query()
            ->whereNull('confirmed_at')
            ->where('created_at', '<', $cutoff)
            ->delete();

        $this->info("Purged {$deleted} unconfirmed subscriber(s) older than {$days} day(s).");

        return self::SUCCESS;
    }
}
