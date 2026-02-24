<?php

namespace App\Providers;

use App\Models\User;
use Laravel\Horizon\Horizon;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\HorizonApplicationServiceProvider;

/**
 * Registers horizon service provider services.
 */
class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    public function boot() : void
    {
        parent::boot();

        Horizon::routeMailNotificationsTo('hello@benjamincrozat.com');
    }

    protected function gate() : void
    {
        Gate::define('viewHorizon', function (User $user) {
            return $user->isAdmin();
        });
    }
}
