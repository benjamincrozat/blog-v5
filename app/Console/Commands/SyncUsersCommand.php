<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Attribute\AsCommand;

// This is a temporary command that I'll remove once I'm in production.
#[AsCommand(name: 'app:sync-users')]
class SyncUsersCommand extends Command
{
    protected $description = 'Fetch users from the legacy database';

    public function handle() : void
    {
        $this->info('Syncing users…');

        DB::connection('legacy')
            ->table('users')
            ->get()
            ->each(function (object $legacyUser) {
                User::query()->updateOrCreate([
                    'id' => $legacyUser->id,
                    'email' => $legacyUser->email,
                ], [
                    'name' => $legacyUser->name,
                    'github_login' => $legacyUser->github_login,
                    'avatar' => json_decode($legacyUser->github_data, true)['avatar'] ?? null,
                    'github_data' => json_decode($legacyUser->github_data),
                    'biography' => 1 !== $legacyUser->id ? null : <<<'MARKDOWN'
Hi! I'm from the South of France and I've been a self-taught web developer since 2006. When I started learning PHP and JavaScript, PHP 4 was still widely used, Internet Explorer 6 ruled the world, and we used DHTML to add falling snow on websites.

Being able to educate myself for free on the web changed my life for the better. Giving back to the community was a natural direction in my career and I truly enjoy it.

Therefore, I decided to take action:

1. I launched this blog in September 2022 with the goal to be in everyone's Google search. I get more than tens of thousands of monthly clicks from it and even more visits overall (my [analytics dashboard](https://benjamincrozat.pirsch.io/?domain=benjamincrozat.com&interval=30d&scale=day) is public by the way).
2. I also started growing my [X (formerly Twitter) account](https://x.com/benjamincrozat) at the same time, which has now over 7,000 followers.
3. All the content I write is free thanks to my sponsors.

I also want to be completely free with my time and make a living with my own products. In April 2024, I launched [Nobinge](https://nobinge.ai/), a tool to summarize and chat with your content, including YouTube videos.

Believe me, I'm just getting started!
MARKDOWN,
                    'created_at' => $legacyUser->created_at,
                    'updated_at' => $legacyUser->updated_at,
                ]);

                $this->info("Synced user {$legacyUser->email}");
            });

        $this->info('All users have been synced.');
    }
}
