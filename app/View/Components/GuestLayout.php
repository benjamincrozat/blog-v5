<?php

namespace App\View\Components;

use Illuminate\View\View;
use Illuminate\View\Component;

class GuestLayout extends Component
{
    public function render() : View
    {
        return view('layouts.guest');
    }
}
