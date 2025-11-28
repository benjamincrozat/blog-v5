<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\View\View;

class Newsletter extends Component
{
    public function render() : View
    {
        return view('livewire.newsletter');
    }
}
