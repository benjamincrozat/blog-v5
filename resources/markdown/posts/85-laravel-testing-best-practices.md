---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/197/2iaZ5lJi19MT8M17ht5m9cmyl3vJpH-metadGVzdGluZy5qcGc%3D-.jpg
Title: "9 testing best practices for Laravel in 2024"
Description: "Are you familiar with testing? Good. Here are a bunch of best practices to help you level up even more!"
Published at: 2023-10-27
Modified at: 2024-01-21
Categories: testing, laravel
---

## Introduction to testing in Laravel

**Writing tests to guarantee a level of stability for the projects you work on will skyrocket your career through the roof.** And that's not an overstatement.

Writing tests also improve your quality of life by freeing you from doing the same things over and over in your web browser or HTTP client. **Your computer will be able to run the tests you wrote a hundred times per hour if necessary.** Unless you are some kind of futuristic cyborg, you cannot reach this level of productivity.

If you are not familiar at all with tests, I wrote an article on Laracasts on how to quickly get started: [Start testing your Laravel code in less than 5 minutes](https://blog.laracasts.com/posts/start-testing-your-laravel-code-in-less-than-5-minutes)

So yeah, writing automated tests can be time-consuming at the start when you are not familiar with the process. But it changes over time. That's the value of investments!

Here's an overview of the value tests bring to the table:
- Write some tests to ensure your code's stability. It takes an additional hour at most when you are familiar with them.
- Deploy to production, discover new bugs, write a failing test, fix the code, make the test pass, and move on with your life. Because there's little chance that the bug you fixed will come back.

Now, here's a common disaster scenario that happens when you don't write tests:
- You write some code and you constantly switch to your browser or HTTP client to test whatever you do and waste a lot of time in the process.
- You deploy to production, there are a lot of bugs, you fix them live, but some of them come back for some reasons you don't understand (we also call that a regression), and you are eventually stuck in an infinite loop of problems.
- Your employer or client is mad at you and the relationship degrades.

Let's take action to avoid this!

**If you prefer a one-to-one call to show me your code and ask whatever question you might have, you can [book a session](/consulting), now.**

![Testing in action.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/203/conversions/8DkDSf9Dql6iBCkx8g4NM2QUoZ7gVL-metaQ2xlYW5TaG90IDIwMjMtMTAtMjcgYXQgMTQuNTkuMDdAMngucG5n--medium.jpg)

## Testing best practices for Laravel developers

### Break the ice with testing using Pest

Pest is based on PHPUnit but adds tons of convenience and can even do things that PHPUnit cannot.

**This great testing framework will declutter your test suite because it requires less boilerplate code to function.**

For instance, take this test written for PHPUnit:

```php
<?php
    
namespace App\Tests\Feature;

use Tests\TestCase;

class SomeFeatureTest extends TestCase
{
    public function test_some_feature_works()
    {
        $this
            ->get(route('some-route'))
            ->assertOk()
            ->assertViewIs('some-view');
    }
}
```

And now, see the difference with Pest:

```php
<?php
    
test('some feature works')
    ->assertOk()
    ->assertViewIs('some-view');
```

Pest has a plethora of features. Here are some examples shamelessly copied and pasted from the documentation:
- Built-in parallel features for faster test runs
- Beautiful documentation that's easy to navigate
- Native profiling tools to optimize slow-running tests
- Out-of-the-box Architectural Testing to test application rules
- Coverage report directly on the terminal to track code coverage
- Dozens of optional plugins, such as Watch Mode and Snapshot testing, to customize Pest to fit your needs.

I suggest you take a look at the [fantastic official documentation](https://pestphp.com) for Pest, but also this great course on Laracasts: [Pest From Scratch](https://laracasts.com/series/pest-from-scratch)

Of course, whether you write your test with Pest or PHPUnit, the result will mostly be the same. This recommendation is targeted at people who are just getting started and want as less friction as possible.

![Pest's official documentation.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/201/conversions/Ta5aeMcaPYRaFAW6eibcqhkZevsdxt-metaQ2xlYW5TaG90IDIwMjMtMTAtMjcgYXQgMTQuNTEuMDFAMngucG5n--medium.jpg)

### Make testing more reliable by using the production stack

Let's take a typical Laravel project; they usually run on the latest version of PHP, MySQL, and Redis.

But you are probably running your tests using SQLite and the array cache driver, right? Well, this is wrong, and let me explain why.

Have you ever run into an issue with MySQL that SQLite doesn't have? Or vice-versa? That's because your tests didn't catch the bug due to the testing environment not mimicking what's in production.

In general, people substitute dependencies to make their tests run faster. But to me, **a reliable test suite beats a quick one anytime**. In the end, you want to offer the best user experience possible to your users.

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit …>
    …
    <php>
        …
        {+<env name="CACHE_DRIVER" value="redis" />+}
        {+<env name="DB_CONNECTION" value="mysql" />+}
    </php>
</phpunit>
```

### Testing web apps require more feature tests than unit tests

**Feature Tests in Laravel are invaluable for ensuring the overall stability of a web application.**

They simulate a user's journey, interacting with both front-end and back-end components as well as the database, providing a holistic assessment of your app.

While they're slower to run than Unit Tests, this comprehensive coverage often catches unintended issues in areas you weren't even targeting.

The extra time spent running Feature Tests is a worthy trade-off for the high level of reliability they offer compared to Unit Tests.

By catching problems across your entire codebase, they give you the confidence that your application will function as expected in the real world.

Of course, nothing is perfect, and other kinds of tests should be run like end-to-end testing (which in our context, consists of running tests in a headless web browser), but also manual reviews performed by human beings.

### Make testing faster and cheaper by faking remote services

Writing tests for remote services like a third-party API is a challenge. There often are rate limits that you can't afford to reach.

So unless there's a test environment like Stripe's API, you will have no other choice than to fake the calls to the service's API.

Laravel provides a lot of fakes to help us simulate some parts of the framework. In our case, we will simulate the [HTTP client](https://laravel.com/docs/http-client).

In parts of my code, I leverage OpenAI's API to add a touch of AI to my project. Here's a simplified example:

```php
use Illuminate\Support\Facades\Http;

Http::withToken(config('services.openai.api_key'))
	->post('https://api.openai.com/v1/chat/completions', [
		'model' => 'gpt-4',
		'messages' => [
			[
				'role' => 'user',
				'content' => 'Some prompt',
			],
		],
	])
	->throw()
	->json('choices.0.message.content');
```

Now, here's how I use Laravel's HTTP client fake to save precious credits and speed up my tests:

```php
Http::fake([
    'api.openai.com/v1/chat/completions' => Http::response([
        'choices' => [[
            'message' => ['content' => 'Lorem ipsum dolor sit amet.'],
        ]],
    ]),
]);
```

Whenever I'm running this test, **the HTTP client will be swapped by a fake one that will just pretend to request the API and receive a response I completely made up**.

How do I know what the faked response should be? Just request whatever API you are trying to fake and you'll see it for yourself. 🙂

You can learn more on the official documentation about [Laravel's fake HTTP client](https://laravel.com/docs/http-client#testing).

### Make testing more useful with Continuous Integration

**The best way to unlock the full potential of tests is to let another computer run them.** This also is called **continuous integration**.

This computer will pull the latest changes in your codebase, run the tests you wrote, and let you know if one of them fails.

This computer can also be a virtual machine, and this virtual machine can also be provided by a free third-party service like [GitHub Actions](https://github.com/features/actions).

Here's how the configuration file for a GitHub Action workflow looks like. If you are working on a Laravel project, you can adapt the following workflow to it. Create a file in *.github/workflows/tests.yml* and paste this code, which is pretty much the same as I use for this blog:

```yaml
name: Tests

on:
  push:
  pull_request:

jobs:
  tests:
    runs-on: ubuntu-latest

    services:
      mysql:
        image: mysql:latest
        env:
          MYSQL_ALLOW_EMPTY_PASSWORD: yes
        ports:
          - 3306
        options: >-
          --health-cmd="mysqladmin ping"
          --health-interval=10s
          --health-timeout=5s
          --health-retries=3

    steps:
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          coverage: none
          php-version: 8.2

      - name: Setup the MySQL database
        run: mysql -u root -h 127.0.0.1 -P ${{ job.services.mysql.ports[3306] }} -e "CREATE DATABASE laravel"

      - name: Setup Redis
        uses: zhulik/redis-action@1.1.0

      - name: Checkout code
        uses: actions/checkout@v3

      - name: Create .env file
        run: cp .env.example .env

      - name: Install back-end dependencies
        run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist

      - name: Generate encryption key
        run: php artisan key:generate

      - name: Install and build front-end dependencies
        run: yarn && yarn build

      - name: Run tests
        run: php artisan test
```

The official documentation for Laravel provides [an example GitHub Actions workflow](https://laravel.com/docs/dusk#running-tests-on-github-actions) to help you run Dusk, which you can take inspiration from.

But before going on GitHub to learn everything about Actions, there's still one practice you can combine with continuous integration to go beyond the God developer level.

![GitHub Actions… in action!](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/202/conversions/WWGFzVSyhYLMf2xkVQnC1Vb7GpCKQ9-metaQ2xlYW5TaG90IDIwMjMtMTAtMjcgYXQgMTQuNTUuMTVAMngucG5n--medium.jpg)

### Make testing more pragmatic with Continuous Delivery

Using GitHub Actions, we saw how to run tests automatically thanks to another computer, and for free.

But **what if we were able to trigger a deployment to our production environment whenever the tests on our *main* branch pass**?

Add the following step:

```yaml
- name: Deploy on production
  run: curl -X POST ${{ secrets.DEPLOYMENT_URL }}
  if: github.ref == 'refs/heads/main' && success()
```

1. We run a command that makes a POST request to a secret URL (in my case, I'm using [Ploi](/recommends/ploi) to manage my server and they offer a hook that you can call for that).
2. This command runs only when the workflow is a success. We also make sure it happens on the main branch only. (With this configuration, the workflow runs on all branches. You can customize that if you want.)

[Learn how to add secrets values in your GitHub Actions](https://docs.github.com/en/actions/security-guides/using-secrets-in-github-actions) so you don't leak confidential credentials to contributors.

### Confirm bug fixes by writing tests

One rock-solid strategy for testing, especially when coupled with CI and CD (continuous integration and continuous deployment, is to **write a test to confirm a bug has been fixed**.

Imagine the following catastrophe scenario:
1. You noticed that your contact form is not checking for a valid sender email.
2. You fix the bug by using Laravel's validator.
3. Some day, you noticed that the bug came back because you unintentionally changed something that affected the contact form. And the worst of this? You have no idea when it happened.

What an embarassement!

Luckily, there's an easy way to make sure this doesn't happen again: **write a test that ensures your contact form only accepts a valid sender email address**.

```php
it('requires a valid email address', function () {
	$this
		->post('/contact', [
			'email' => 'some-invalid-email-address',
			'message' => fake()->paragraph(),
		])
		->assertInvalid(['email']);
});
```

Now, each time you push code to your Git repository, your CI environment will run the tests and prevent deployments in case the data coming from your contact form isn't validated as expected.

### Make testing more joyful by not overthinking it

When you get comfortable with testing, you will be tempted to anticipate every edge case.

In my experience, **the real problems are almost impossible to anticipate**. Let your users discover them (that's inevitable), and fix them once and for all by writing tests for them just like we discussed earlier.

**My strategy is to write tests for the happy paths** (e.g. "test the contact form works as expected") **and the obvious non-happy paths** (e.g. "test the contact form needs a valid email address"). That's it. After that, let your users uncovered the trickier bugs.

That being said, most of the time, your users won't tell you what's wrong with your applications and they'll just leave. Therefore, **you must use the appropriate tooling if you want to passively collect valuable feedback**.

Error monitoring tools like [Flare](/recommends/flare) or Sentry can notify you whenever an error has been encountered by your users. They provide tons of technical information to help you pinpoint which part of your code is at fault, you can mark issues as resolved and get a notification in case of a regression, and more!

That being said, if you apply the strategy I gave you in the previous section, you shouldn't get any regression. Every one of your bug fixes will stick like perpetual glue. 😎

### Provide input and test the output

Tests are about providing an input and testing that you get the expected output.

**The way your code is implemented is irrelevant.** Good tests don't need to change if the specifications of the feature don't change.

Imagine: you have a feature that generates an image dynamically depending on some input. You may have used GD until now, but it's time to switch to Imagick because it supports a feature GD doesn't have or has fewer bugs.

You shouldn't have to change your test to ensure the feature works. In that case, I'd just constrain the project to the latest major version of the extension:

```bash
composer require ext-imagick:^3.7
```

## But wait, there's more than that

If you want to keep leveling up in testing, you may be interested in checking out [Battle Ready Laravel](/recommends/battle-ready-laravel) by Ash Allen.

This book has been a compelling read so far. I’m not the kind of person who finishes books in one go, but I can tell you it’s packed with tons of actionable tips and expert advice to help you ensure your Laravel apps are not just built, but wait for it… battle-ready! And testing is a big (really big) part of this. If you're looking to enhance your application's robustness, this book is a must.

I won’t disclose everything inside it, but the website provides a pretty good overview and even a free sample.

[Check out Battle Ready Laravel](/recommends/battle-ready-laravel)

[![Battle Ready Laravel by Ash Allen](https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/7b6f4c49-cf20-49a7-bdd2-3f01f414df29)](/recommends/battle-ready-laravel)
