<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPolicy
{
    use HandlesAuthorization;

    public function before(User $user) : ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user) : bool
    {
        return false;
    }

    public function view(User $user, Job $job) : bool
    {
        return false;
    }

    public function create(User $user) : bool
    {
        return false;
    }

    public function update(User $user, Job $job) : bool
    {
        return false;
    }

    public function delete(User $user, Job $job) : bool
    {
        return false;
    }

    public function deleteAny(User $user) : bool
    {
        return false;
    }

    public function restore(User $user, Job $job) : bool
    {
        return false;
    }

    public function forceDelete(User $user, Job $job) : bool
    {
        return false;
    }
}
