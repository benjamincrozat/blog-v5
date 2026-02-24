<?php

namespace App\Actions;

use Github\Client;
use App\Models\User;
use Github\Exception\RuntimeException;
use Github\Exception\ApiLimitExceedException;

/**
 * Executes the refresh user data action.
 */
class RefreshUserData
{
    /**
     * Refresh the user's data from GitHub.
     */
    public function refresh(User $user) : void
    {
        try {
            $data = app(Client::class)
                ->api('user')
                ->showById($user->github_id);

            $githubData = $user->github_data ?? [];
            $githubData['user'] = $data;

            $user->update([
                'github_data' => $githubData,
                'refreshed_at' => now(),
            ]);
        } catch (ApiLimitExceedException $e) {
            // Let's do nothing and patiently wait for the reset.
        } catch (RuntimeException $e) {
            if ('Not Found' === $e->getMessage()) {
                $user->delete();
            }
        }
    }
}
