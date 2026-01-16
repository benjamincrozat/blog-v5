<?php

use App\Livewire\Search;

use function Pest\Livewire\livewire;

it('shows a message when no posts or links are found', function () {
    livewire(Search::class)
        ->set('query', 'test')
        ->assertSee('No posts found for "test".', false)
        ->assertSee('No links found for "test".', false);
});

it('treats array query payloads as empty input', function () {
    livewire(Search::class)
        ->set('query', [])
        ->assertSee('Try to type something…', false);
});
