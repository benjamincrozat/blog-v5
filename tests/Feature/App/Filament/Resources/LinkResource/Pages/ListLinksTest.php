<?php

use App\Models\Link;
use App\Models\User;
use App\Jobs\CreatePostForLink;
use App\Notifications\LinkApproved;

use function Pest\Laravel\actingAs;

use Illuminate\Support\Facades\Bus;

use function Pest\Livewire\livewire;

use Illuminate\Support\Facades\Notification;
use App\Filament\Resources\LinkResource\Pages\ListLinks;

beforeEach(function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    /** @var User $admin */
    actingAs($admin);

    Bus::fake([CreatePostForLink::class]);
    Notification::fake([LinkApproved::class]);
});

it('can approve a link and generate a post from the table action', function () {
    $link = Link::factory()->create([
        'post_id' => null,
        'is_approved' => null,
        'is_declined' => null,
    ]);

    livewire(ListLinks::class)
        ->callTableAction('approve', $link, data: [
            'notes' => 'Some notes for post generation.',
        ])
        ->assertHasNoTableActionErrors();

    $link->refresh();

    expect($link->isApproved())->toBeTrue();
    expect($link->notes)->toBe('Some notes for post generation.');

    Bus::assertDispatched(CreatePostForLink::class);
    Notification::assertSentTo($link->user, LinkApproved::class);
});

it('can approve a link without generating a post from the table action', function () {
    $link = Link::factory()->create([
        'post_id' => null,
        'is_approved' => null,
        'is_declined' => null,
    ]);

    $component = livewire(ListLinks::class)
        ->mountTableAction('approve', $link);

    expect($component->getMountedActionModalHtml())
        ->toContain('Approve without post');

    $component
        ->setTableActionData([
            'notes' => 'Notes, but do not generate a post.',
        ])
        ->callMountedTableAction(['generate_post' => false])
        ->assertHasNoTableActionErrors();

    $link->refresh();

    expect($link->isApproved())->toBeTrue();
    expect($link->notes)->toBe('Notes, but do not generate a post.');

    Bus::assertNotDispatched(CreatePostForLink::class);
    Notification::assertSentTo($link->user, LinkApproved::class);
});
