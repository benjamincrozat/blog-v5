<?php

namespace App\Providers;

use Livewire\Livewire;
use Carbon\CarbonImmutable;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\View;
use App\Livewire\LinkWizard\FirstStep;
use App\Livewire\LinkWizard\LinkWizard;
use App\Livewire\LinkWizard\SecondStep;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use App\Contracts\PostImageScreenshotter;
use App\Filesystem\CloudflareImagesAdapter;
use Illuminate\Filesystem\FilesystemAdapter;
use App\Support\BrowsershotPostImageScreenshotter;

/**
 * Sets shared application rules and bindings when Laravel starts.
 *
 * It connects the screenshot interface to Browsershot and uses immutable dates.
 * It also registers the link wizard and sets Eloquent safety rules. Finally, it
 * adds the Cloudflare Images disk and gives every view the current user. These
 * settings affect every web request and command.
 */
class AppServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->bind(PostImageScreenshotter::class, BrowsershotPostImageScreenshotter::class);
    }

    public function boot() : void
    {
        Date::use(CarbonImmutable::class);

        Livewire::component('link-wizard', LinkWizard::class);
        Livewire::component('first-step', FirstStep::class);
        Livewire::component('second-step', SecondStep::class);

        Model::automaticallyEagerLoadRelationships();

        Model::shouldBeStrict(! app()->isProduction());

        Model::unguard();

        Storage::extend('cloudflare-images', function ($app, array $config) {
            $adapter = new CloudflareImagesAdapter(
                config('services.cloudflare_images.token'),
                config('services.cloudflare_images.account_id'),
                config('services.cloudflare_images.account_hash'),
                $config['variant'] ?? 'public',
            );

            $filesystem = new Filesystem($adapter);

            return new FilesystemAdapter($filesystem, $adapter, $config);
        });

        View::composer('*', fn (\Illuminate\View\View $view) => $view->with([
            'user' => auth()->user(),
        ]));
    }
}
