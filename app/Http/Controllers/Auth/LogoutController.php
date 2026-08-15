<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

/**
 * Signs the current user out and returns them to the home page.
 *
 * It clears the old session and creates a new form token before logging out, so
 * old session data cannot be reused. The next page receives a one-time message
 * confirming that sign-out finished.
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
