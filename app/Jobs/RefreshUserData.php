<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Defines the RefreshUserData implementation.
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
