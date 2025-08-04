<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Question;
use Livewire\Attributes\Computed;

class Quiz extends Component
{
    public ?\App\Models\Quiz $quiz = null;

    /**
     * Zero-based pointer to the current question.
     * `null` means we are still on the intro screen.
     */
    public ?int $index = null;

    /**
     * User answers, keyed by question ID.
     *
     * @var array<int,int>
     */
    public array $answers = [];

    public bool $results = false;

    public function steps() : int
    {
        return $this->quiz?->questions->count() ?? 0;
    }

    #[Computed]
    public function currentStep() : int
    {
        return is_null($this->index) ? 0 : $this->index + 1;
    }

    #[Computed]
    public function currentQuestion() : ?Question
    {
        return is_null($this->index)
            ? null
            : $this->quiz->questions->values()[$this->index] ?? null;
    }

    #[Computed]
    public function isLastQuestion() : bool
    {
        return ! is_null($this->index) && $this->index === $this->steps() - 1;
    }

    #[Computed]
    public function hasNextStep() : bool
    {
        return false === $this->results;
    }

    public function next() : void
    {
        $this->validate([
            'answers.*' => ['required', 'integer'],
        ]);

        // If all questions have been answered, show results.
        if (count($this->answers) === $this->quiz->questions->count()) {
            $this->results = true;

            return;
        }

        // If index is null, we are on the intro screen and we need to set it
        // to 0 because ncrementing directly from null sets the value to 1.
        if (is_null($this->index)) {
            $this->index = 0;
        } elseif (! $this->isLastQuestion) {
            $this->index++;
        }
    }
}
