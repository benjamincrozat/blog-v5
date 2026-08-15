<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Notifications\Welcome;
use App\Http\Controllers\Controller;
use App\Notifications\NewUserCreated;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

/**
 * Finishes GitHub sign-in and starts the visitor's remembered blog session.
 *
 * It creates or updates the local user by email, saves the latest GitHub data,
 * and signs the user in. A new account receives the welcome email and alerts the
 * site owner. A returning user gets no signup email and continues to the page
 * they originally wanted.
 */
class GithubAuthCallbackController extends Controller
{
    public function __invoke() : RedirectResponse
    {
        $githubUser = Socialite::driver('github')->user();

        // Either create a brand new user or update their information.
        $user = User::query()->updateOrCreate(['email' => $githubUser->getEmail()], [
            'name' => $githubUser->getName() ?? $githubUser->getNickname(),
            'github_id' => $githubUser->getId(),
            'github_login' => $githubUser->getNickname(),
            'avatar' => $githubUser->getAvatar(),
            'github_data' => (array) $githubUser,
            'email' => $githubUser->getEmail(),
            'refreshed_at' => now(),
        ]);

        auth()->login($user, true);

        if ($user->wasRecentlyCreated) {
            $user->notify(new Welcome);

            User::query()
                ->where('github_login', 'benjamincrozat')
                ->first()
                ?->notify(new NewUserCreated($user));
        }

        return redirect()->intended()->with('status', 'You have been logged in.');
    }
}
