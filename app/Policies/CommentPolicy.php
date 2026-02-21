<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;

/**
 * Defines the CommentPolicy implementation.
 */
class CommentPolicy
{
    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function delete(User $user, Comment $comment) : bool
    {
        return $comment->user->is($user);
    }

    public function create(User $user) : bool
    {
        return true;
    }
}
