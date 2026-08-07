<?php

use Filament\Panel;
use App\Models\User;

it('generates a slug from the name', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
    ]);

    expect($user->slug)->toBe('john-doe');
});

it('derives profile data from github metadata when biography is missing', function () {
    $user = User::factory()->create([
        'biography' => null,
        'github_data' => [
            'user' => [
                'bio' => 'GitHub bio.',
                'blog' => 'https://example.com',
                'company' => 'Example Inc.',
                'html_url' => 'https://github.com/example',
            ],
        ],
    ]);

    expect($user->about)->toBe('GitHub bio.');
    expect($user->blogUrl)->toBe('https://example.com');
    expect($user->company)->toBe('Example Inc.');
    expect($user->githubUrl)->toBe('https://github.com/example');
});

it('builds a GitHub profile URL when refreshed profile data is missing', function () {
    $user = User::factory()->create([
        'github_login' => 'older-user',
        'github_data' => ['id' => 123],
    ]);

    expect($user->githubUrl)->toBe('https://github.com/older-user');
});

it('detects admins and panel access based on the GitHub login', function () {
    $admin = User::factory()->make([
        'github_login' => 'benjamincrozat',
    ]);

    $regular = User::factory()->make([
        'github_login' => 'other-user',
    ]);

    $panel = Mockery::mock(Panel::class);

    expect($admin->isAdmin())->toBeTrue();
    expect($admin->canAccessPanel($panel))->toBeTrue();

    expect($regular->isAdmin())->toBeFalse();
    expect($regular->canAccessPanel($panel))->toBeFalse();
});
