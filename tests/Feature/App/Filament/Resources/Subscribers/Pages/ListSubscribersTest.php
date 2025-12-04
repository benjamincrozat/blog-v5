<?php

use App\Models\User;
use Spatie\Tags\Tag;
use App\Models\Subscriber;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;
use App\Notifications\ConfirmSubscription;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\assertDatabaseMissing;

use App\Filament\Resources\Subscribers\Pages\ListSubscribers;

beforeEach(function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);
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

it('searches subscribers by email from the table', function () {
    $match = Subscriber::factory()->create([
        'email' => 'search-me@example.com',
    ]);

    $other = Subscriber::factory()->create([
        'email' => 'someone-else@example.com',
    ]);

    livewire(ListSubscribers::class)
        ->searchTable('search-me@example.com')
        ->assertCanSeeTableRecords([$match])
        ->assertCanNotSeeTableRecords([$other]);
});

it('sorts subscribers by newest first', function () {
    $older = Subscriber::factory()->create([
        'created_at' => now()->subDay(),
    ]);

    $newest = Subscriber::factory()->create();

    livewire(ListSubscribers::class)
        ->assertCanSeeTableRecords([$newest, $older], inOrder: true);
});

it('shows correct status labels in the table', function () {
    $pending = Subscriber::factory()->create([
        'confirmed_at' => null,
    ]);

    $confirmed = Subscriber::factory()->create([
        'confirmed_at' => now(),
    ]);

    livewire(ListSubscribers::class)
        ->assertTableColumnStateSet('status', 'Pending', record: $pending)
        ->assertTableColumnStateSet('status', 'Confirmed', record: $confirmed);
});

it('shows the right table actions based on status', function () {
    $pending = Subscriber::factory()->create([
        'confirmed_at' => null,
    ]);

    $confirmed = Subscriber::factory()->create([
        'confirmed_at' => now(),
    ]);

    livewire(ListSubscribers::class)
        ->assertActionVisible(TestAction::make('resend')->table($pending))
        ->assertActionVisible(TestAction::make('markConfirmed')->table($pending))
        ->assertActionHidden(TestAction::make('markPending')->table($pending));

    livewire(ListSubscribers::class)
        ->assertActionHidden(TestAction::make('resend')->table($confirmed))
        ->assertActionHidden(TestAction::make('markConfirmed')->table($confirmed))
        ->assertActionVisible(TestAction::make('markPending')->table($confirmed));
});

it('deletes subscribers with the bulk action', function () {
    $subscribers = Subscriber::factory()->count(2)->create();

    livewire(ListSubscribers::class)
        ->selectTableRecords($subscribers)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertCountTableRecords(0);

    $subscribers->each(
        fn (Subscriber $subscriber) => assertDatabaseMissing('subscribers', ['id' => $subscriber->id])
    );
});

it('renders expected table columns and filters', function () {
    livewire(ListSubscribers::class)
        ->assertTableColumnExists('email')
        ->assertTableColumnExists('tags')
        ->assertTableColumnExists('status')
        ->assertTableColumnExists('confirmation_sent_at')
        ->assertTableColumnExists('created_at')
        ->assertTableFilterExists('confirmed')
        ->assertTableFilterExists('tags');
});

it('shows empty state content with no subscribers', function () {
    livewire(ListSubscribers::class)
        ->assertSeeText('No subscribers yet')
        ->assertSeeText('New signups from the newsletter will show up here automatically.');
});

it('notifies when performing table actions', function () {
    Notification::fake();

    $pending = Subscriber::factory()->create([
        'confirmed_at' => null,
        'confirmation_sent_at' => null,
    ]);

    livewire(ListSubscribers::class)
        ->callTableAction('resend', $pending)
        ->assertNotified('Confirmation email sent to ' . $pending->email);

    Notification::assertSentTo($pending, ConfirmSubscription::class);

    livewire(ListSubscribers::class)
        ->callTableAction('markConfirmed', $pending)
        ->assertNotified('Subscriber marked as confirmed.');

    $confirmed = Subscriber::factory()->create([
        'confirmed_at' => now(),
    ]);

    livewire(ListSubscribers::class)
        ->callTableAction('markPending', $confirmed)
        ->assertNotified('Subscriber moved back to pending.');
});
