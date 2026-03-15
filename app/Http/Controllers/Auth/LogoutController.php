<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

/**
 * Logs the current user out and resets the session state.
 */
class LogoutController extends Controller
{
    public function __invoke(Request $request) : RedirectResponse
    {
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        auth()->logout();

        return to_route('home')->with('status', 'You have been successfully logged out.');
    }
}
