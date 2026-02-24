<?php

namespace App\Livewire\LinkWizard;

use Spatie\LivewireWizard\Components\WizardComponent;

/**
 * Renders and coordinates the link wizard Livewire component.
 */
class LinkWizard extends WizardComponent
{
    public function steps() : array
    {
        return [
            FirstStep::class,
            SecondStep::class,
        ];
    }
}
