<?php

namespace App\Livewire\LinkWizard;

use Spatie\LivewireWizard\Components\WizardComponent;

/**
 * Defines the ordered steps for the public link submission wizard.
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
