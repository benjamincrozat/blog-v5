<?php

use App\Models\User;
use App\Models\Revision;
use App\Policies\RevisionPolicy;

it('only allows updating revisions', function () {
    $user = User::factory()->make();
    $revision = Revision::factory()->make();

    $policy = new RevisionPolicy;

    expect($policy->create($user))->toBeFalse();
    expect($policy->update($user, $revision))->toBeTrue();
});
