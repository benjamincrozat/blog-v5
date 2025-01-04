<?php

use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;

it('allows access to authorized users', function () {
    $user = User::factory()->create([
        'name' => 'Benjamin Crozat',
        'email' => 'hello@benjamincrozat.com',
    ]);

    actingAs($user)
        ->get(route('horizon.index'))
        ->assertOk();
});

it('disallows access to unauthorized users', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('horizon.index'))
        ->assertForbidden();
});

it('disallows access to guests', function () {
    get(route('horizon.index'))
        ->assertForbidden();
});
