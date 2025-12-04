<?php

use App\Models\User;
use App\Models\Subscriber;

use function Pest\Laravel\actingAs;

use App\Filament\Resources\Subscribers\SubscriberResource;

beforeEach(function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);
});

it('shows the pending navigation badge only when needed', function () {
    Subscriber::factory()->count(2)->create([
        'confirmed_at' => null,
    ]);

    Subscriber::factory()->create([
        'confirmed_at' => now(),
    ]);

    expect(SubscriberResource::getNavigationBadge())->toBe('2');

    Subscriber::query()->delete();

    expect(SubscriberResource::getNavigationBadge())->toBeNull();
});

it('returns confirmation status in global search details', function () {
    $pending = Subscriber::factory()->create([
        'confirmed_at' => null,
    ]);

    $confirmed = Subscriber::factory()->create([
        'confirmed_at' => now(),
    ]);

    expect(SubscriberResource::getGlobalSearchResultDetails($pending))->toBe([
        'Status' => 'Pending',
    ]);

    expect(SubscriberResource::getGlobalSearchResultDetails($confirmed))->toBe([
        'Status' => 'Confirmed',
    ]);
});
