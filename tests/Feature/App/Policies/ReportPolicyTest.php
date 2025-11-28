<?php

use App\Models\User;
use App\Models\Report;
use App\Policies\ReportPolicy;

it('allows updating reports but not creating them', function () {
    $user = User::factory()->make();
    $report = Report::factory()->make();

    $policy = new ReportPolicy;

    expect($policy->create($user))->toBeFalse();
    expect($policy->update($user, $report))->toBeTrue();
});
