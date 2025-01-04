---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/256/01HFEMVGDB82X8N51ZE7EYA0MG.jpg
Title: "Laravel Pulse: monitor your apps for free"
Description: "Discover Laravel Pulse, a free, open-source package offering real-time app monitoring, usage statistics, queue monitoring, and more."
Published at: 2023-11-17
Modified at: 2023-12-05
Categories: laravel, packages
---

## What Laravel Pulse is

**[Laravel Pulse](https://pulse.laravel.com) is a free and open source package for the [Laravel framework](https://laravel.com) that helps developers monitor various aspects of their web applications in real-time.**

Taylor Otwell, the creator of Laravel, [said](https://twitter.com/taylorotwell/status/1725210034399797365) that the tool was born out of frustration he had with Laravel Forge and its inability to quickly diagnose why the service was underperforming and which users were causing that.

I still can't believe such a tool can be free, but there we are!

![Laravel Pulse's dashboard in action.](https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/255/conversions/01HFEMTCB9RCHDFGV1EZYRGVV5-medium.jpg)

## Laravel Pulse release date

**Laravel Pulse was released on December 1, 2023 as a beta version.** I still have no idea when the first stable version will be released, but I will update this article as soon as I know.

## The features Laravel Pulse offers

- **Application usage of your users**: Laravel Pulse lets you see which of your users consume the most resources. It uncovers which ones make the most requests, engage with the slowest endpoints, and dispatch the most jobs throughout your application.
- **Statistics of your servers**: Monitor various aspects of your servers, like CPU, memory, and disk usage. All of this in one place!
- **Queue monitoring**: Instead of trying to guess which queue needs more resources, make educated decisions based on historical data and bring real benefits to your users. 👌
- **Performance monitoring**: Again, making decisions based on data is invaluable, and Laravel Pulse can also help with routes, database queries, jobs, and even outgoing requests.
- **Trending exceptions**: It's like having a highly lightweight error-tracking tool. You will see which exceptions are the most frequent and how they could be related to your performance issues.
- **Custom community-powered metrics**: Laravel Pulse will undoubtedly be customizable, and I'll share whatever I can find from the community here on my blog.
- **Custom dashboard layout**: It's mentionned on the official website that the dashboard's layout can be customized, which is great news!

## Install Laravel Pulse

**For now, Laravel Pulse requires a MySQL database. If you are running something else, that's fine, but you will have to create a new database connection for MySQL.**

**Pulse is still in beta** and you have to make some changes to your *composer.json* file to install it until a stable version is released. Change the `minimum-stability` to `beta` and make sure `prefer-stable` is set to `true`:

```json
"minimum-stability": "beta",
"prefer-stable": true
```

Then, just to install Pulse, use the following command:

```bash
composer require laravel/pulse
```

## Set up Laravel Pulse

To set up Laravel Pulse, you will need to ensure it has a database in which it can store the data it collects. You can do this by publishing the migrations:

```bash
php artisan vendor:publish --provider="Laravel\Pulse\PulseServiceProvider"
```

And running them:

```bash
php artisan migrate
```

Once this is done, open your browser and hit the route `/pulse`. It was that simple.

![Laravel Pulse right after it has been installed.](https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/16b61b08-db6d-47ee-8439-ae8c34ba371f)

## Let Laravel Pulse monitor your server

Right now, our new installation of Pulse is empty.

We have to display information in there and I suggest we start with your server's resources.

To do this, run the following command:

```bash
php artisan pulse:check
```

This command runs continuously to provide Pulse with the needed data for its . This is a daemon you have to run in the background, and I recommend you to use [Supervisor](http://supervisord.org) to do so.

![Laravel Pulse's php artisan pulse:check command in action.](https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/6ce523e5-1294-41d9-94ee-dfffec241bdc)

## Make Laravel Pulse secure

Securing access to Laravel Pulse can be done in the same way as Laravel Horizon for instance. In your `AuthServiceProvider`, define a `viewPulse` Gate and do whatever check is necessary. In my case, I check for the correct email address:

```php
namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot() : void
    {
        Gate::define('viewPulse', fn (User $user) => in_array($user->email, [
            'johndoe@example.com',
            …
        ]));
    }
}
```

## Install custom cards made by the community

The Laravel community is already working on custom cards to make it even more useful. Here's the link to the article where I try to gather the best ones I've found: [The best custom cards for Laravel Pulse](/best-laravel-pulse-custom-cards)

## Contribute to Laravel Pulse

Laravel Pulse's is free, open source, and is available through a GitHub repository at [laravel/pulse](https://github.com/laravel/pulse). You can send as many Pull Requests as you want for bug fixes and enhancements.

![Laravel Pulse's GitHub repository.](https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/64cc4a49-c450-48c1-a1f3-32a7473725ee)

## Laravel Pulse troubleshooting

### Laravel Pulse returns a 404 not found error

For anyone having a 404 after installing Laravel Pulse, here's a potential solution: You may have a wildcard hijacking the `pulse` route.

Here are possible fixes:
- Change the `path` configuration value in `config/pulse.php` to something like `/pulse/dashboard`.
- Or a more elegant solution would be to filter your wildcard route like so (which is what I did for this blog):

```php
Route::get('/{post:slug}', [PostController::class, 'show'])->name('posts.show')
    ->where('post', '^(?!pulse$).*$');
```

Basically, we are instructing Laravel to match the route only if it isn't `pulse`.

### My Laravel Pulse dashboard is empty

If your Laravel Pulse dashboard is empty, chances are that there's a problem with Livewire. If you open your developer tools and check for errors, you will most likely see a 404 not found error on */livewire/livewire.js*. Luckily, I wrote about this recurring issue caused by how Livewire serves its JavaScript by default: [Fix the /livewire/livewire.js 404 not found error](https://benjamincrozat.com/livewire-js-404-not-found)
