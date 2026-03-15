<?php

use App\Models\User;

use function Pest\Laravel\get;

use Illuminate\Pagination\LengthAwarePaginator;

it('shows an author only when they have published posts', function () {
    $user = User::factory()
        ->hasPosts(2, ['published_at' => now()])
        ->create();

    get(route('authors.show', $user->slug))
        ->assertOk()
        ->assertViewIs('authors.show')
        ->assertViewHas('author', $user)
        ->assertSee('"@type": "ProfilePage"', escape: false)
        ->assertViewHas('posts', function (LengthAwarePaginator $posts) {
            expect($posts->count())->toBe(2);

            return true;
        });
});

it('renders the author about fallback when biography is empty', function () {
    $user = User::factory()
        ->hasPosts(1, ['published_at' => now()])
        ->create([
            'biography' => null,
            'github_data' => [
                'user' => [
                    'bio' => 'Writes about queues and Laravel.',
                    'company' => 'Acme',
                ],
            ],
        ]);

    get(route('authors.show', $user->slug))
        ->assertOk()
        ->assertSee('Writes about queues and Laravel.');
});

it('shows an author when they only have approved links', function () {
    $user = User::factory()
        ->hasLinks(2, ['is_approved' => now()])
        ->create();

    get(route('authors.show', $user->slug))
        ->assertOk()
        ->assertViewIs('authors.show')
        ->assertViewHas('author', $user)
        ->assertViewHas('links', function (LengthAwarePaginator $links) {
            expect($links->count())->toBe(2);

            return true;
        });
});

it('returns 404 when the author has no published posts or approved links', function () {
    $user = User::factory()
        ->hasPosts(1, ['published_at' => null])
        ->hasLinks(1, ['is_approved' => null, 'is_declined' => null])
        ->create();

    get(route('authors.show', $user->slug))
        ->assertNotFound();
});
