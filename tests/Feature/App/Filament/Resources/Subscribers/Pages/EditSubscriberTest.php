<?php

use App\Models\User;
use App\Models\Subscriber;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

use App\Filament\Resources\Subscribers\Pages\EditSubscriber;

beforeEach(function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);
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

it('allows keeping the same email when editing', function () {
    $subscriber = Subscriber::factory()->create([
        'email' => 'keep@example.com',
    ]);

    livewire(EditSubscriber::class, ['record' => $subscriber->getKey()])
        ->fillForm([
            'email' => 'keep@example.com',
        ])
        ->call('save')
        ->assertHasNoFormErrors();
});
