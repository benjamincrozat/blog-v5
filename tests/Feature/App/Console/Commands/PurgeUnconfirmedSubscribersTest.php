<?php

use App\Models\Subscriber;

use function Pest\Laravel\artisan;

use App\Console\Commands\PurgeUnconfirmedSubscribers;

it('purges only subscribers who never confirmed within the configured window', function () {
    $staleSubscriber = Subscriber::factory()->create([
        'confirmed_at' => null,
        'created_at' => now()->subDays(40),
    ]);

    $recentSubscriber = Subscriber::factory()->create([
        'confirmed_at' => null,
        'created_at' => now()->subDays(5),
    ]);

    $confirmedSubscriber = Subscriber::factory()->create([
        'confirmed_at' => now(),
        'created_at' => now()->subDays(100),
    ]);

    artisan(PurgeUnconfirmedSubscribers::class, ['--days' => 30])->assertSuccessful();

    expect(Subscriber::query()->find($staleSubscriber->id))->toBeNull();
    expect(Subscriber::query()->find($recentSubscriber->id))->not->toBeNull();
    expect(Subscriber::query()->find($confirmedSubscriber->id))->not->toBeNull();
});
