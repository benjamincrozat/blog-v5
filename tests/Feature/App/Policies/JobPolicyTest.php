<?php

use App\Models\Job;
use App\Models\User;
use App\Policies\JobPolicy;

it('grants admins access via the before hook', function () {
    $admin = User::factory()->make([
        'github_login' => 'benjamincrozat',
    ]);

    expect((new JobPolicy)->before($admin))->toBeTrue();
});

it('denies every ability to non-admin users', function () {
    $user = User::factory()->make([
        'github_login' => 'someone-else',
    ]);

    $job = Job::factory()->make();

    $policy = new JobPolicy;

    expect($policy->before($user))->toBeNull();
    expect($policy->viewAny($user))->toBeFalse();
    expect($policy->view($user, $job))->toBeFalse();
    expect($policy->create($user))->toBeFalse();
    expect($policy->update($user, $job))->toBeFalse();
    expect($policy->delete($user, $job))->toBeFalse();
    expect($policy->deleteAny($user))->toBeFalse();
    expect($policy->restore($user, $job))->toBeFalse();
    expect($policy->forceDelete($user, $job))->toBeFalse();
});
