<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;

/**
 * Renders and coordinates the comment form Livewire component.
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
