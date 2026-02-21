<?php

use App\Models\User;
use Mockery\MockInterface;
use App\Jobs\RefreshUserData;
use App\Actions\RefreshUserData as RefreshUserDataAction;

it('delegates refreshing user data', function () {
    $user = User::factory()->make();

    $action = mock(RefreshUserDataAction::class, function (MockInterface $mock) use ($user) {
        $mock->shouldReceive('refresh')
            ->once()
            ->with($user);
    });

    app()->instance(RefreshUserDataAction::class, $action);

    (new RefreshUserData($user))->handle();
});
