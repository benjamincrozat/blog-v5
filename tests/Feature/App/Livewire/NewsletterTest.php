<?php

use App\Models\Subscriber;
use Illuminate\Support\Str;
use App\Livewire\Newsletter;
use Spatie\Honeypot\Honeypot;
use Spatie\Honeypot\EncryptedTime;

use function Pest\Livewire\livewire;

use App\Notifications\ConfirmSubscription;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseEmpty;

it('renders', function () {
    livewire(Newsletter::class)
        ->assertOk()
        ->assertSet('subscribed', false);
});

it('subscribes a user and queues a confirmation email', function () {
    Notification::fake();

    assertDatabaseEmpty(Subscriber::class);

    $honeypot = app(Honeypot::class);

    livewire(Newsletter::class)
        ->set("honeypot.data.{$honeypot->validFromFieldName()}", (string) EncryptedTime::create(now()->subSeconds(5)))
        ->set('email', 'test@example.com')
        ->call('subscribe')
        ->assertRedirect(route('newsletter'))
        ->assertSessionHas('status', 'Thanks for subscribing! A confirmation email has been sent to your inbox.')
        ->assertSessionHas('status_type', 'success');

    $subscriber = Subscriber::query()->first();

    expect($subscriber->confirmation_token)->not->toBeNull();
    expect($subscriber->confirmation_sent_at)->not->toBeNull();
    expect($subscriber->hasTag('general'))->toBeTrue();

    Notification::assertSentTo($subscriber, ConfirmSubscription::class);
});

it('does not subscribe a user if the email is invalid', function () {
    $honeypot = app(Honeypot::class);

    livewire(Newsletter::class)
        ->set("honeypot.data.{$honeypot->validFromFieldName()}", (string) EncryptedTime::create(now()->subSeconds(5)))
        ->set('email', 'invalid-email')
        ->call('subscribe')
        ->assertHasErrors(['email']);

    assertDatabaseEmpty(Subscriber::class);
});

it('resends confirmation when the subscriber already exists but is unconfirmed', function () {
    Notification::fake();

    $subscriber = Subscriber::factory()->create([
        'email' => 'test@example.com',
        'confirmation_token' => $previousToken = hash('sha256', Str::random(40)),
        'confirmation_sent_at' => $previousSentAt = now()->subDay(),
    ])->attachTag('foo');

    $honeypot = app(Honeypot::class);

    livewire(Newsletter::class)
        ->set("honeypot.data.{$honeypot->validFromFieldName()}", (string) EncryptedTime::create(now()->subSeconds(5)))
        ->set('email', 'test@example.com')
        ->call('subscribe')
        ->assertRedirect(route('newsletter'))
        ->assertSessionHas('status', 'Another confirmation email is on its way!')
        ->assertSessionHas('status_type', 'success');

    $subscriber->refresh();

    expect($subscriber->confirmation_token)->not->toEqual($previousToken);
    expect($subscriber->confirmation_sent_at->greaterThan($previousSentAt))->toBeTrue();
    expect($subscriber->hasTag('foo'))->toBeTrue();
    expect($subscriber->tags)->toHaveCount(1);

    Notification::assertSentTo($subscriber, ConfirmSubscription::class);

    assertDatabaseCount(Subscriber::class, 1);
});

it('does not subscribe a user if the email is already confirmed', function () {
    Subscriber::factory()->create([
        'email' => 'test@example.com',
        'confirmed_at' => now(),
    ]);

    $honeypot = app(Honeypot::class);

    livewire(Newsletter::class)
        ->set("honeypot.data.{$honeypot->validFromFieldName()}", (string) EncryptedTime::create(now()->subSeconds(5)))
        ->set('email', 'test@example.com')
        ->call('subscribe')
        ->assertRedirect(route('newsletter'))
        ->assertSessionHas('status', 'You already are subscribed.')
        ->assertSessionHas('status_type', 'error');

    assertDatabaseCount(Subscriber::class, 1);
});

it('blocks spam submissions with the honeypot', function () {
    $honeypot = app(Honeypot::class);

    $nameField = $honeypot->unrandomizedNameFieldName();
    $validFromField = $honeypot->validFromFieldName();

    livewire(Newsletter::class)
        ->set("honeypot.data.{$validFromField}", (string) EncryptedTime::create(now()->subSeconds(5)))
        ->set("honeypot.data.{$nameField}", 'bot')
        ->call('subscribe')
        ->assertStatus(403);

    assertDatabaseEmpty(Subscriber::class);
});
