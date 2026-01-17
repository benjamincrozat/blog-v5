<?php

use App\Jobs\CreatePostForLink;
use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

use App\Filament\Resources\LinkResource\Pages\ListLinks;

beforeEach(function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);

    Bus::fake();
    Notification::fake();
});

it('approves a link without generating a post from the table action', function () {
    $link = Link::factory()->create([
        'is_approved' => null,
        'is_declined' => null,
        'post_id' => null,
    ]);

    livewire(ListLinks::class)
        ->callTableAction('approve_without_post', $link)
        ->assertNotified('The link has been approved.');

    $link->refresh();

    expect($link->is_approved)->not->toBeNull();
    expect($link->post_id)->toBeNull();

    Bus::assertNotDispatched(CreatePostForLink::class);
});
