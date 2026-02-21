<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Revision;

/**
 * Defines the RevisionPolicy implementation.
 */
class RevisionPolicy
{
    public function create(User $user) : bool
    {
        return false;
    }

    public function update(User $user, Revision $revision) : bool
    {
        return true;
    }
}
