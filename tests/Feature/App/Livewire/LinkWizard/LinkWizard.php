<?php

use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

use Illuminate\Support\Facades\Route;
use App\Livewire\LinkWizard\LinkWizard;
use Livewire\Mechanisms\HandleRouting\LivewirePageController;

it('renders', function () {
    $user = User::factory()->create();

    actingAs($user);

    livewire(LinkWizard::class)
        ->assertOk();
});

it('disallows guests', function () {
    get(route('links.create'))
        ->assertRedirect(route('login'));
});

it('registers the create route as a Livewire page', function () {
    $route = Route::getRoutes()->getByName('links.create');

    expect($route)->not->toBeNull();
    expect($route->getActionName())->toBe(LivewirePageController::class);
    expect($route->action['livewire_component'] ?? null)->toBe(LinkWizard::class);
});
