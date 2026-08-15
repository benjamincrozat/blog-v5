<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GithubProvider;

/**
 * Starts GitHub sign-in without losing the page the visitor meant to return to.
 *
 * It saves the previous URL only when another flow, such as the link wizard, has
 * not already chosen a return page. It then sends the visitor to GitHub through
 * Socialite and asks for the email address needed to find the local account.
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
