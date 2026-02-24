<?php

use App\Models\Link;
use App\Models\User;
use App\Notifications\LinkApproved;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

use Illuminate\Support\Facades\Notification;
use App\Filament\Resources\LinkResource\Pages\ListLinks;

beforeEach(function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    /** @var User $admin */
    actingAs($admin);

    Notification::fake();
});

it('can approve a link from the table action', function () {
    $link = Link::factory()->create([
        'post_id' => null,
        'is_approved' => null,
        'is_declined' => null,
    ]);

    livewire(ListLinks::class)
        ->callTableAction('approve', $link, data: [
            'notes' => 'Some moderation notes.',
        ])
        ->assertHasNoTableActionErrors();

    $link->refresh();

    expect($link->isApproved())->toBeTrue();
    expect($link->notes)->toBe('Some moderation notes.');

    Notification::assertSentTo($link->user, LinkApproved::class);
});

it('does not show a generate-post option in the approve modal', function () {
    $link = Link::factory()->create([
        'post_id' => null,
        'is_approved' => null,
        'is_declined' => null,
    ]);

    $component = livewire(ListLinks::class)
        ->mountTableAction('approve', $link);

    expect($component->getMountedActionModalHtml())
        ->not->toContain('Approve without post');
});
