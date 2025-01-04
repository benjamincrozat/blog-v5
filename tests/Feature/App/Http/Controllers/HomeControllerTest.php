<?php

use function Pest\Laravel\get;

it('renders', function () {
    get(route('home'))
        ->assertOk();
});
