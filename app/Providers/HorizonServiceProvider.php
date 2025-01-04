<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    protected function gate() : void
    {
        Gate::define('viewHorizon', function ($user) {
            // It's super important to check for a verified email address first. Otherwise,
            // anyone can register with one of my email addresses and mess everything up.
            return $user->email_verified_at && in_array($user->email, [
                'hello@benjamincrozat.com',
            ]);
        });
    }
}
