<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Notifications\Welcome;
use App\Http\Controllers\Controller;
use App\Notifications\NewUserCreated;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

/**
 * Handles github auth callback controller requests.
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
