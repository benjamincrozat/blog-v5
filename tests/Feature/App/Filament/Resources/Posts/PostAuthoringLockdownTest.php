<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

use Symfony\Component\Routing\Exception\RouteNotFoundException;

beforeEach(function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);
});

it('does not register create or edit routes for posts', function () {
    expect(fn () => route('filament.admin.resources.posts.create'))
        ->toThrow(RouteNotFoundException::class);

    expect(fn () => route('filament.admin.resources.posts.edit', 'post-slug'))
        ->toThrow(RouteNotFoundException::class);
});
