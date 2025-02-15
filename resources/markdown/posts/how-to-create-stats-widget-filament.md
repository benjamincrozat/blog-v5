---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/2655bb68-f53f-4188-b25e-e2cfcd222751
Title: "How to create a stats widget in Filament"
Description: "Learn how to create and customize a stats widget in Filament for displaying real-time statistics."
Published at: 2024-06-06
Categories: filament
---

## Introduction

Let me walk you through creating a custom stats widget in Filament to display real-time statistics on your dashboard.

Filament can be used outside of Laravel as well, but for this article, I'll assume you're using the framework.

## Creating the Stats Widget

Let’s start by generating the necessary widget structure with the following command:

```bash
php artisan make:filament-widget PostsStats --stats-overview
```

Then, enter the name of the resource you want to display statistics for. In this case, we'll use `PostResource`. And finally, select the panel you want (most of use will select "the admin panel").

This spins up a widget class in `app/Filament/Resources/PostResource/Widgets/PostsStats.php`, which is your canvas for the next steps, where we'll inject some life into those stats!

## Registering the Widget in Your Resource

First, you have to register the widget in your resource class. Open `app/Filament/Resources/PostResource/PostResource.php` and add the following method:

```php
use App\Filament\Resources\PostResource\Widgets\PostsStats;

class PostResource extends Resource
{
    public static function getWidgets(): array
    {
        return [
            PostsStats::class,
        ];
    }
}
```

Then , modify `app/Filament/Resources/PostResource/Pages/ListPosts.php` to have your widget displayed above the list of posts:

```php
use App\Filament\Widgets\PostsStats;

public function getHeaderWidgets(): array
{
    return [
        PostsStats::class,
    ];
}
```

For now, the widget won't display because we haven't added any data to it. Let's fix that next.

## Feeding the Widget with Data

Navigate to your widget class at `app/Filament/Widgets/PostsStats.php`. Here, let's set up some dynamic fetching of data from our `Post` model:

```php
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget\Stat;

protected function getStats(): array
{
    return [
        Stat::make('Count', Post::count()),
        Stat::make('Views', Post::sum('views')),
        Stat::make('Average read time', Post::average('read_time')),
    ];
}
```

You can add a description to a stat to make sure context is provided:

```php
Stat::make('Average read time', Post::average('read_time'))
    ->description('This is based on an average read speed of 200 words per minute.');
```

Finally, if you need big numbers to be formatted, you can use the `Number::format()` method provided by Laravel:

```php
use Illuminate\Support\Number;

Stat::make('Views', Number::format(Post::sum('views'))),
```

## Automatically Refresh the Widget

For our example, this isn't super useful, unless you have an army of writers constantly publishing content, but know that you can automatically refresh a widget at a given interval. In `app/Filament/Widgets/PostsStats.php`, adjust the `$pollingInterval`:

```php
protected static ?string $pollingInterval = '3s';
```

## Conclusion

And that's it! You've now equipped your Filament admin panel with a custom stats widget tailored for monitoring blog posts statistics. Now, tweak it, make it your own, test it, and deploy it!

You can also learn more about [stats widgets](https://filamentphp.com/docs/3.x/widgets/stats-overview) on the official documentation.
