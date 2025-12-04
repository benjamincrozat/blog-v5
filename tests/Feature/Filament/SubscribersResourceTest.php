<?php

use App\Models\User;
use Spatie\Tags\Tag;
use App\Models\Subscriber;

use function Pest\Livewire\livewire;

use App\Notifications\ConfirmSubscription;
use Illuminate\Support\Facades\Notification;
use App\Filament\Resources\Subscribers\Pages\EditSubscriber;
use App\Filament\Resources\Subscribers\Pages\ListSubscribers;
use App\Filament\Resources\Subscribers\Pages\CreateSubscriber;

beforeEach(function () {
    $this->admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    $this->actingAs($this->admin);
});

it('displays subscribers with their tags and status in Filament', function () {
    $subscriber = Subscriber::factory()->create([
        'confirmed_at' => now(),
        'confirmation_sent_at' => now(),
    ]);

    $subscriber->attachTag('general');

    livewire(ListSubscribers::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords([$subscriber])
        ->assertSeeText('general')
        ->assertTableActionExists('resend')
        ->assertTableActionExists('markConfirmed')
        ->assertTableActionExists('markPending');
});

it('filters subscribers by status and tags', function () {
    $target = Subscriber::factory()->create([
        'confirmed_at' => null,
    ]);

    $tag = Tag::findOrCreate('foo');
    $target->attachTag($tag);

    $other = Subscriber::factory()->create([
        'confirmed_at' => now(),
    ]);

    livewire(ListSubscribers::class)
        ->filterTable('tags', $tag->getKey())
        ->assertCanSeeTableRecords([$target])
        ->assertCanNotSeeTableRecords([$other]);

    livewire(ListSubscribers::class)
        ->filterTable('confirmed', true)
        ->assertCanSeeTableRecords([$other])
        ->assertCanNotSeeTableRecords([$target]);
});

it('executes subscriber management actions from the table', function () {
    Notification::fake();

    $pending = Subscriber::factory()->create([
        'confirmed_at' => null,
        'confirmation_sent_at' => null,
    ]);

    livewire(ListSubscribers::class)
        ->callTableAction('resend', $pending);

    Notification::assertSentTo($pending, ConfirmSubscription::class);
    expect($pending->fresh()->confirmation_sent_at)->not->toBeNull();

    livewire(ListSubscribers::class)
        ->callTableAction('markConfirmed', $pending);

    expect($pending->fresh()->confirmed_at)->not->toBeNull();

    $confirmed = Subscriber::factory()->create([
        'confirmed_at' => now(),
    ]);

    livewire(ListSubscribers::class)
        ->callTableAction('markPending', $confirmed);

    expect($confirmed->fresh()->confirmed_at)->toBeNull();
});

it('creates subscribers with tags from the Filament form', function () {
    $tag = Tag::findOrCreate('general');

    livewire(CreateSubscriber::class)
        ->fillForm([
            'email' => 'filament@example.com',
            'tags' => [$tag->getKey()],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $subscriber = Subscriber::query()->where('email', 'filament@example.com')->first();

    expect($subscriber)->not->toBeNull();
    expect($subscriber?->hasTag('general'))->toBeTrue();
});

it('validates the subscriber schema on create', function () {
    livewire(CreateSubscriber::class)
        ->fillForm([
            'email' => 'invalid',
        ])
        ->call('create')
        ->assertHasFormErrors(['email' => 'email']);
});

it('exposes subscriber schema sections', function () {
    livewire(CreateSubscriber::class)
        ->assertSchemaComponentExists('subscriber-section')
        ->assertSchemaComponentExists('status-section');
});

it('loads subscriber data when editing the resource', function () {
    $subscriber = Subscriber::factory()->create([
        'email' => 'edit-me@example.com',
        'confirmed_at' => now(),
    ]);

    livewire(EditSubscriber::class, ['record' => $subscriber->getKey()])
        ->assertFormSet([
            'email' => 'edit-me@example.com',
        ]);
});
