<?php

use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Str;

use function Pest\Laravel\getJson;
use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

use App\Livewire\LinkWizard\SecondStep;

use function Pest\Laravel\assertDatabaseHas;

use Illuminate\Support\Facades\Notification;
use App\Notifications\LinkWaitingForValidation;

class TestableSecondStep extends SecondStep
{
    public bool $wentBack = false;

    public bool $dispatched = false;

    public function previousStep() : void
    {
        $this->wentBack = true;
    }

    public function dispatch($event, ...$parameters)
    {
        $this->dispatched = true;

        return new class
        {
            public function self() : void {}
        };
    }
}

it('submits the link and notifies the admin', function () {
    Notification::fake();

    $url = 'https://example.com';

    cache()->forever('embed_' . Str::slug($url, '_'), [
        'image_url' => 'https://example.com/image.png',
        'title' => 'Example title',
        'description' => 'Example description',
    ]);

    $user = User::factory()->create();

    $admin = User::factory()->create(['github_login' => 'benjamincrozat']);

    actingAs($user)
        ->get(route('links.create'))
        ->assertOk();

    livewire(SecondStep::class, [
        'url' => $url,
        'imageUrl' => 'https://example.com/image.png',
        'title' => 'Example title',
        'description' => 'Example description',
    ])
        ->assertDispatched('fetch')
        ->call('fetch')
        ->call('submit')
        ->assertRedirect(route('links.index', ['submitted' => true]));

    assertDatabaseHas(Link::class, [
        'url' => $url,
        'image_url' => 'https://example.com/image.png',
        'title' => 'Example title',
        'description' => 'Example description',
    ]);

    Notification::assertSentToTimes($admin, LinkWaitingForValidation::class, 1);
});

it("doesn't allow guests", function () {
    getJson(route('links.create'))
        ->assertUnauthorized();
});

it('falls back to previous step when no url is provided on mount', function () {
    $component = app(TestableSecondStep::class);
    $component->url = '';

    $component->mount();

    expect($component->wentBack)->toBeTrue();
    expect($component->dispatched)->toBeTrue();
});

it('fetches metadata when cache is empty', function () {
    cache()->forget('embed_' . Str::slug('https://fetch.me', '_'));

    $embed = new class
    {
        public int $calls = 0;

        public function get(string $url) : object
        {
            $this->calls++;

            return (object) [
                'image' => 'https://foo.test/image.png',
                'title' => 'Fetched title',
                'description' => 'Fetched description',
            ];
        }
    };

    app()->instance(\Embed\Embed::class, $embed);

    livewire(SecondStep::class, ['url' => 'https://fetch.me'])
        ->call('fetch')
        ->assertSet('imageUrl', 'https://foo.test/image.png')
        ->assertSet('title', 'Fetched title')
        ->assertSet('description', 'Fetched description');

    expect($embed->calls)->toBe(1);

    livewire(SecondStep::class, ['url' => 'https://fetch.me'])
        ->call('fetch')
        ->assertSet('title', 'Fetched title');

    expect($embed->calls)->toBe(1);
});
