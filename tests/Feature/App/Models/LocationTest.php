<?php

use App\Models\Location;

it('builds a display name from available parts', function (array $attributes, string $expected) {
    $location = Location::factory()->create($attributes);

    expect($location->display_name)->toBe($expected);
})->with([
    'city region country' => [
        [
            'city' => 'Springfield',
            'region' => 'Illinois',
            'country' => 'United States',
        ],
        'Springfield, Illinois, United States',
    ],
    'city country' => [
        [
            'city' => 'Paris',
            'region' => null,
            'country' => 'France',
        ],
        'Paris, France',
    ],
    'country only' => [
        [
            'city' => null,
            'region' => null,
            'country' => 'Germany',
        ],
        'Germany',
    ],
    'city only' => [
        [
            'city' => 'Toronto',
            'region' => null,
            'country' => '',
        ],
        'Toronto',
    ],
    'city region' => [
        [
            'city' => 'Vancouver',
            'region' => 'British Columbia',
            'country' => '',
        ],
        'Vancouver, British Columbia',
    ],
]);
