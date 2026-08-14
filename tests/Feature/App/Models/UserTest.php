<?php

use App\Models\User;

it('uses the refreshed GitHub profile URL when available', function () {
    $user = User::factory()->create([
        'github_data' => [
            'user' => [
                'html_url' => 'https://github.com/example',
            ],
        ],
    ]);

    expect($user->githubUrl)->toBe('https://github.com/example');
});

it('builds a GitHub profile URL when refreshed profile data is missing', function () {
    $user = User::factory()->create([
        'github_login' => 'older-user',
        'github_data' => ['id' => 123],
    ]);

    expect($user->githubUrl)->toBe('https://github.com/older-user');
});

it('detects admins based on the GitHub login', function () {
    $admin = User::factory()->make([
        'github_login' => 'benjamincrozat',
    ]);

    $regular = User::factory()->make([
        'github_login' => 'other-user',
    ]);

    expect($admin->isAdmin())->toBeTrue();
    expect($regular->isAdmin())->toBeFalse();
});
