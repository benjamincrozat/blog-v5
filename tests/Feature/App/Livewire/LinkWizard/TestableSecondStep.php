<?php

namespace Tests\Feature\App\Livewire\LinkWizard;

use App\Livewire\LinkWizard\SecondStep;

class TestableSecondStep extends SecondStep
{
    public bool $wentBack = false;

    public bool $dispatched = false;

    public function previousStep() : void
    {
        $this->wentBack = true;
    }

    public function dispatch($event, ...$parameters)
    {
        $this->dispatched = true;

        return new class
        {
            public function self() : void {}
        };
    }
}
