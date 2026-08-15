<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Jobs\RefreshUserData;
use Illuminate\Console\Command;

/**
 * Queues GitHub profile refreshes for one user or every stale user.
 *
 * A given value can match the local ID, name, GitHub ID, or GitHub login. Without
 * one, users that were never refreshed or are more than one day old are queued
 * five seconds apart to reduce GitHub rate limits. The jobs contact GitHub; this
 * command only schedules them.
 */
class RefreshUserDataCommand extends Command
{
    protected $signature = 'app:refresh-user-data {id?}';

    protected $description = "Refresh a given user's data from GitHub. If no ID is provided, all users will be refreshed if their data is older than a day.";

    public function handle() : void
    {
        if ($id = $this->argument('id')) {
            RefreshUserData::dispatch(
                User::query()
                    ->where('id', $id)
                    ->orWhere('name', $id)
                    ->orWhere('github_id', $id)
                    ->orWhere('github_login', $id)
                    ->firstOrFail()
            );

            $this->info('User has been queued for a refresh.');

            return;
        }

        $users = User::query()
            ->whereNull('refreshed_at')
            ->orWhere('refreshed_at', '<=', now()->subDay())
            ->get()
            ->each(
                fn (User $user, int $index) => RefreshUserData::dispatch($user)
                    // To avoid being banned by GitHub, we delay each job by 5 seconds.
                    ->delay(now()->addSeconds($index * 5))
            );

        $this->info($users->count() . ' user(s) have been queued for a refresh.');
    }
}
