<?php

namespace App\Providers;

use App\Models\User;
use Laravel\Horizon\Horizon;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\HorizonApplicationServiceProvider;

/**
 * Sets access and email alerts for the Horizon queue dashboard.
 *
 * Laravel's Horizon setup runs first. This provider then sends failure emails to
 * the operations inbox and allows only the fixed site administrator to open the
 * dashboard.
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
