<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Comment;
use Livewire\Component;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Notifications\NewReply;
use Livewire\Attributes\Locked;
use App\Notifications\NewComment;

/**
 * Manages comment pagination, submissions, and deletion for a post thread.
 */
class Comments extends Component
{
    use WithPagination;

    #[Locked]
    public int $postId;

    public string $sort = 'desc';

    /**
     * Used in the view to track which comment is being replied to.
     */
    public ?int $parentId = null;

    public function render() : View
    {
        return view('livewire.comments', [
            'comments' => Comment::query()
                ->where('post_id', $this->postId)
                ->whereNull('parent_id')
                ->orderBy('created_at', $this->sort)
                ->paginate(10),

            'commentsCount' => Comment::query()
                ->where('post_id', $this->postId)
                ->count(),
        ]);
    }

    #[On('comment.submitted')]
    public function store(?int $parentId = null, string $commentContent = '') : void
    {
        if (auth()->guest()) {
            abort(401);
        }

        $comment = Comment::query()->create([
            'post_id' => $this->postId,
            'user_id' => auth()->id(),
            'parent_id' => $parentId,
            'content' => $commentContent,
        ]);

        // If this is a reply, we notify the parent comment's author.
        if ($parentId) {
            Comment::query()
                ->find($parentId)
                ?->user
                ->notify(new NewReply($comment));
        }

        // We notify the admin when a new comment is
        // posted unless it's the admin himself.
        if ('benjamincrozat' !== auth()->user()->github_login) {
            User::query()
                ->where('github_login', 'benjamincrozat')
                ->first()
                ?->notify(new NewComment($comment));
        }

        $this->reset('parentId');
    }

    public function delete(int $commentId) : void
    {
        if (auth()->guest()) {
            abort(401);
        }

        $comment = Comment::query()->find($commentId);

        if (auth()->user()->cannot('delete', $comment)) {
            abort(403);
        }

        if ($comment && auth()->user()->can('delete', $comment)) {
            $comment->deleteWithChildren();
        }
    }
}
