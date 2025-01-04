---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/46/Prompts_wvauit.jpg
Title: "Laravel Prompts: build delightful Artisan commands"
Description: "Delight your users. Learn how to create beautiful Artisan Commands using Laravel Prompts."
Published at: 2023-07-20
Modified at: 2023-09-20
Categories: laravel, packages
---

## Introduction

[Laravel Prompts](https://laravel.com/docs/prompts) was introduced by [Jess Archer](https://jessarcher.com) at [Laracon US](https://laracon.us) on July 19, 2023.

The package is widely used in Laravel to drastically improve the developer experience and you can also take advantage of it.

This is a tutorial to help you get started with it.

## Requirements for Laravel Prompts

**Laravel Prompts only supports macOS, Linux, and Windows with WSL.** Outside of WSL, it will fall back onto Symfony's implementation of the various features Laravel Prompts offers.

You will also need at least Laravel 10 and PHP 8.1.

## How to install Laravel Prompts

Laravel Prompts only requires the package to be installed. There's no configuration file or service provider to publish.

```bash
composer require laravel/prompts
```

Now, we can start building awesome prompts for your Artisan commands.

## Learn the basics by building

If you ever built your own Artisan commands, you will appreciate how easy to use Laravel Prompts is.

Let's create a new command for the sake of this tutorial:

```bash
php artisan make:command MakePokemonCommand
```

Now, instead of the following code:

```php
namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakePokemonCommand extends Command
{
    protected $signature = 'make:pokemon';

    protected $description = 'Create a new Pokémon';

    public function handle()
    {
        $name = $this->ask('What should the name be?');
	  
        …
    }
}
```

You can use the functions Laravel Prompts provides and enjoy an improved output:

```php
namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use function Laravel\Prompts\text;

class MakePokemonCommand extends Command
{
    protected $signature = 'make:pokemon';

    protected $description = 'Create a new Pokémon';

    public function handle()
    {
        $name = text('What should the name be?');

        Pokemon::create(compact('name'));

        $this->info("$name was successfully created!");
    }
}
```

Easy, right?

Here's a screenshot of the command in action.

![Our make:pokemon command in action thanks to Laravel Prompts](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/166/conversions/CleanShot_2023-08-01_at_18.57.46_2x_dsvzpj-medium.jpg)

## Add a multi-select

Let's go even further and add type selection:

```php
$types = multiselect(
    label: 'What types should your Pokémon have?',
    options: [
        'Bug', 'Dark', 'Dragon', 'Electric', 'Fairy', 'Fighting', 'Fire', 'Flying', 'Ghost', 'Grass', 'Ground', 'Ice', 'Normal', 'Poison', 'Psychic', 'Rock', 'Steel', 'Water',
    ],
    required: true,
    scroll: 10,
    validate: function (array $values) {
	    return ! in_array(count($values), [1, 2])
            ? 'A maximum of two types can be assigned to a Pokémon.'
            : null;
	}
);
```

- Have the 18 possible types as options.
- The prompt is required to have an answer.
- We display 10 choices at once.
- We ensure that 1 or 2 types only can be assigned.
- We leverage PHP's named arguments to keep our code informative and omit some arguments.

Here's how the refreshed Artisan command using Laravel Prompts looks:

![make:pokemon with multiselect and validation.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/167/conversions/CleanShot_2023-08-01_at_19.20.09_2x_dlwdhj-medium.jpg)

Laravel Prompts is so easy to use!

## Add a spinner (loading animation)

Laravel Prompts lets you use a beautiful loading animation, effortlessly. Just as the rest of the API, it's as simple as calling the `spin()` function:

```php
use function Laravel\Prompts\spin;
 
$response = spin(
    fn () => Http::get('http://example.com'),
    'Fetching response...'
);
```

According to the documentation, the spin function requires the [pcntl PHP extension](https://www.php.net/manual/fr/book.pcntl.php) to trigger the animation. When it's not available, a static version of the spinner will appear instead.

Now that you got the gist of Laravel Prompts, you can refer to the [super easy to understand documentation](https://laravel.com/docs/prompts) and continue your journey alone.

## Contribute to Laravel Prompts

If you ever encounter a bug or feel like Laravel Prompts needs one more feature, you might want to send your pull requests directly to the official repository: https://github.com/laravel/prompts

