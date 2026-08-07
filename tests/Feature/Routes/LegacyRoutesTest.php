<?php

use function Pest\Laravel\get;

it('redirects /nobinge to external URL with 301', function () {
    get('/nobinge')
        ->assertStatus(301)
        ->assertRedirect('https://nobinge.ai');
});
