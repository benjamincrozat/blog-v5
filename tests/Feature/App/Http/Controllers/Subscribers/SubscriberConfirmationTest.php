<?php

use App\Models\User;
use App\Models\Subscriber;
use Illuminate\Support\Str;

use function Pest\Laravel\get;

use Illuminate\Support\Facades\URL;
use App\Notifications\SubscriberConfirmed;
use Illuminate\Support\Facades\Notification;

it('confirms a subscriber when the token is valid', function () {
    $token = Str::random(40);

    $subscriber = Subscriber::factory()->create([
        'confirmation_token' => hash('sha256', $token),
        'confirmation_sent_at' => now()->subMinutes(5),
    ]);

    $url = URL::temporarySignedRoute('subscribers.confirm', now()->addMinutes(5), [
        'subscriber' => $subscriber->id,
        'token' => $token,
    ]);

    get($url)
        ->assertRedirect(route('newsletter'))
        ->assertSessionHas('status', 'Thanks for confirming your subscription!')
        ->assertSessionHas('status_type', 'success');

    expect($subscriber->fresh()->confirmed_at)->not->toBeNull();
});

it('does not confirm a subscriber when the token is invalid', function () {
    $subscriber = Subscriber::factory()->create([
        'confirmation_token' => hash('sha256', Str::random(40)),
        'confirmation_sent_at' => now()->subMinutes(5),
    ]);

    $url = URL::temporarySignedRoute('subscribers.confirm', now()->addMinutes(5), [
        'subscriber' => $subscriber->id,
        'token' => Str::random(40),
    ]);

    get($url)
        ->assertRedirect(route('newsletter'))
        ->assertSessionHas('status', 'This confirmation link is invalid or has expired.')
        ->assertSessionHas('status_type', 'error');

    expect($subscriber->fresh()->confirmed_at)->toBeNull();
});

it('tells the user when the subscription is already confirmed', function () {
    $subscriber = Subscriber::factory()->create([
        'confirmed_at' => now(),
    ]);

    $url = URL::temporarySignedRoute('subscribers.confirm', now()->addMinutes(5), [
        'subscriber' => $subscriber->id,
        'token' => Str::random(40),
    ]);

    get($url)
        ->assertRedirect(route('newsletter'))
        ->assertSessionHas('status', 'Thanks, but you already confirmed your subscription.')
        ->assertSessionHas('status_type', 'info');
});

it('notifies the admin when the subscriber confirms', function () {
    Notification::fake();

    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    $token = Str::random(40);

    $subscriber = Subscriber::factory()->create([
        'confirmation_token' => hash('sha256', $token),
        'confirmation_sent_at' => now()->subMinutes(5),
    ]);

    $url = URL::temporarySignedRoute('subscribers.confirm', now()->addMinutes(5), [
        'subscriber' => $subscriber->id,
        'token' => $token,
    ]);

    get($url);

    Notification::assertSentToTimes($admin, SubscriberConfirmed::class, 1);
});
