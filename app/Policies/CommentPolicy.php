<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;

/**
 * Decides who can create or delete a blog comment.
 *
 * Any signed-in user may comment. A user may delete their own comment, and the
 * fixed site administrator may delete any comment. Routes and Livewire reject
 * guests before this policy runs.
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
