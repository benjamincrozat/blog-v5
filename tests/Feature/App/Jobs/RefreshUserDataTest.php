<?php

use App\Models\User;
use App\Jobs\RefreshUserData;
use Facades\App\Actions\RefreshUserData as RefreshUserDataAction;

it('delegates refreshing user data', function () {
    $user = User::factory()->make();

    RefreshUserDataAction::shouldReceive('refresh')
        ->once()
        ->with($user);

    (new RefreshUserData($user))->handle();
});
