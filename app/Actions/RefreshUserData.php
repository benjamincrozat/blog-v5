<?php

namespace App\Actions;

use Github\Client;
use App\Models\User;
use Github\Exception\RuntimeException;
use Github\Exception\ApiLimitExceedException;

/**
 * Updates the stored GitHub profile data for an existing blog user.
 *
 * A queued job looks up the account by its GitHub ID. Success saves the returned
 * user data and refresh time. A rate limit leaves the record for a later run.
 * GitHub's Not Found response deletes the stale user. Other GitHub errors leave
 * the local record unchanged.
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
