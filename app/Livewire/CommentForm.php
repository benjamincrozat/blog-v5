<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;

/**
 * Checks the form input for a new comment or reply.
 *
 * The user must be signed in. An optional parent comment must exist, and the text
 * must contain at least three characters. Valid input is sent to the Comments
 * component and the form is cleared. This class does not save or delete comments
 * and does not send notifications.
 */
class CommentForm extends Component
{
    #[Validate('nullable|exists:comments,id')]
    public ?int $parentId = null;

    public ?string $label = null;

    #[Validate('required|string|min:3')]
    public string $commentContent = '';

    public function submit() : void
    {
        if (auth()->guest()) {
            abort(401);
        }

        $this->validate();

        $this->dispatch('comment.submitted', $this->parentId, $this->commentContent);

        $this->reset();
    }
}
