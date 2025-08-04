<?php

use App\Livewire\Quiz;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Quiz::class)
        ->assertStatus(200);
});
