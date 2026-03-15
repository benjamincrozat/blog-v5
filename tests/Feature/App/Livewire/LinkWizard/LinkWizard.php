<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

use App\Livewire\LinkWizard\LinkWizard;

it('renders', function () {
    $user = User::factory()->create();

    actingAs($user);

    livewire(LinkWizard::class)
        ->assertOk();
});

it('disallows guests', function () {
    livewire(LinkWizard::class)
        ->assertRedirect(route('login'));
});
