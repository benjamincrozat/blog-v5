<?php

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;

it('does not register forge heartbeat callbacks when urls are missing', function () {
    $events = collect(app(Schedule::class)->events());

    $syncSearchConsoleSitemap = $events->first(
        fn (Event $event) => str_contains($event->command, 'app:sync-search-console-sitemap')
    );
    $refreshUserData = $events->first(
        fn (Event $event) => str_contains($event->command, 'app:refresh-user-data')
    );

    expect($syncSearchConsoleSitemap)->not->toBeNull()
        ->and($refreshUserData)->not->toBeNull()
        ->and(scheduledAfterCallbacks($syncSearchConsoleSitemap))->toBeEmpty()
        ->and(scheduledAfterCallbacks($refreshUserData))->toBeEmpty();
});

function scheduledAfterCallbacks(Event $event) : array
{
    $property = new ReflectionProperty($event, 'afterCallbacks');
    $property->setAccessible(true);

    return $property->getValue($event);
}
