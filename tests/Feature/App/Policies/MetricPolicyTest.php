<?php

use App\Models\User;
use App\Models\Metric;
use App\Policies\MetricPolicy;

it('always denies metric mutations', function () {
    $user = User::factory()->make();
    $metric = Metric::factory()->make();
    $policy = new MetricPolicy;

    expect($policy->create($user))->toBeFalse();
    expect($policy->update($user, $metric))->toBeFalse();
    expect($policy->delete($user, $metric))->toBeFalse();
});
