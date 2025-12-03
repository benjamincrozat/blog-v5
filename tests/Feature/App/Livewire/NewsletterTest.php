<?php

use App\Models\Subscriber;
use App\Livewire\Newsletter;

use function Pest\Livewire\livewire;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseEmpty;

it('renders', function () {
    livewire(Newsletter::class)
        ->assertOk()
        ->assertSet('subscribed', false);
});

it('subscribes a user', function () {
    assertDatabaseEmpty(Subscriber::class);

    livewire(Newsletter::class)
        ->set('email', 'test@example.com')
        ->call('subscribe')
        ->assertSet('subscribed', true)
        ->assertSet('email', '');

    assertDatabaseHas(Subscriber::class, [
        'email' => 'test@example.com',
    ]);
});

it('does not subscribe a user if the email is invalid', function () {
    livewire(Newsletter::class)
        ->set('email', 'invalid-email')
        ->call('subscribe')
        ->assertHasErrors(['email']);

    assertDatabaseEmpty(Subscriber::class);
});

it('does not subscribe a user if the email is already subscribed', function () {
    Subscriber::query()->create([
        'email' => 'test@example.com',
    ]);

    livewire(Newsletter::class)
        ->set('email', 'test@example.com')
        ->call('subscribe')
        ->assertHasErrors(['email' => 'unique']);

    assertDatabaseCount(Subscriber::class, 1);
});
