<?php

use App\Models\User;
use Spatie\Tags\Tag;
use App\Models\Subscriber;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

use App\Filament\Resources\Subscribers\Pages\CreateSubscriber;

beforeEach(function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);
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

it('validates unique subscriber emails on create', function () {
    Subscriber::factory()->create([
        'email' => 'duplicate@example.com',
    ]);

    livewire(CreateSubscriber::class)
        ->fillForm([
            'email' => 'duplicate@example.com',
        ])
        ->call('create')
        ->assertHasFormErrors(['email' => 'unique']);
});
