<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GithubProvider;

/**
 * Starts the GitHub OAuth flow and preserves the intended return URL.
 */
class GithubAuthRedirectController extends Controller
{
    public function __invoke() : RedirectResponse
    {
        // This helps the user not lose their current page.
        // We only do it if no intended URL is set like
        // in the LinkWizard component for instance.
        if (! redirect()->getIntendedUrl()) {
            redirect()->setIntendedUrl(url()->previous());
        }

        /** @var GithubProvider */
        $github = Socialite::driver('github');

        return $github
            ->scopes(['user:email'])
            ->redirect();
    }
}
