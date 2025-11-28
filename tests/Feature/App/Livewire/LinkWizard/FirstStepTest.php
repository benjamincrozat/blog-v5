<?php

use App\Models\Link;
use Illuminate\Http\Client\Request;

use function Pest\Livewire\livewire;

use Illuminate\Support\Facades\Http;
use App\Livewire\LinkWizard\FirstStep;

it('submits the link', function () {
    Http::fake();

    livewire(FirstStep::class)
        ->set('url', 'https://example.com')
        ->call('submit')
        ->assertDispatched('nextStep');

    Http::assertSent(function (Request $request) {
        return 'https://example.com' === $request->url();
    });
});

it('requires a URL', function () {
    livewire(FirstStep::class)
        ->call('submit')
        ->assertHasErrors(['url' => 'required']);
});

it('requires a valid URL', function () {
    livewire(FirstStep::class)
        ->set('url', 'example')
        ->call('submit')
        ->assertHasErrors(['url' => 'url']);
});

it('ensures the URL is unique', function () {
    Link::factory()->create(['url' => 'https://example.com']);

    livewire(FirstStep::class)
        ->set('url', 'https://example.com')
        ->call('submit')
        ->assertHasErrors(['url' => 'unique']);
});

it('automatically advances when mounting with a pre-filled url', function () {
    Http::fake();

    livewire(FirstStep::class, ['url' => 'https://example.com'])
        ->assertDispatched('nextStep');
});

it('surfaces a validation error when the URL cannot be reached', function () {
    Http::fake(fn () => throw new \Illuminate\Http\Client\ConnectionException);

    livewire(FirstStep::class)
        ->set('url', 'https://example.com')
        ->call('submit')
        ->assertHasErrors(['url']);
});
