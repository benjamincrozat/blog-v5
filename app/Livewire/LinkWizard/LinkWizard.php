<?php

namespace App\Livewire\LinkWizard;

use Spatie\LivewireWizard\Components\WizardComponent;

/**
 * Sets the two steps used by signed-in users to submit a community link.
 *
 * The URL check always comes before the details form and database write. The
 * wizard package carries the form state and moves the user between those steps.
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
