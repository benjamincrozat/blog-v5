<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

use Illuminate\Support\Facades\Route;

beforeEach(function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);
});

it('does not register a create page', function () {
    expect(Route::has('filament.admin.resources.jobs.create'))->toBeFalse();
});
