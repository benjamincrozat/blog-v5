<?php

use Illuminate\Support\Facades\View;

if (! function_exists('jetstream')) {
    function jetstream(): void
    {
        View::getFinder()->setPaths([resource_path('views/jetstream')]);

        config()->set('livewire.view_path', resource_path('views/jetstream'));
    }
}
