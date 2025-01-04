<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HealthController extends Controller
{
    public function __invoke(): View
    {
        return view('health');
    }
}
