<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Runs one user's GitHub profile refresh in the queue.
 *
 * The job stores the target user and hands the real GitHub and database work to
 * the RefreshUserData action. The command can therefore spread many jobs over
 * time without copying the rules for provider errors or saved profile data.
 */
class RefreshUserData implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user,
    ) {}

    public function handle() : void
    {
        app(\App\Actions\RefreshUserData::class)->refresh($this->user);
    }
}
