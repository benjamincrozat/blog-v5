<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Report;

/**
 * Defines the ReportPolicy implementation.
 */
class ReportPolicy
{
    public function create(User $user) : bool
    {
        return false;
    }

    public function update(User $user, Report $report) : bool
    {
        return true;
    }
}
