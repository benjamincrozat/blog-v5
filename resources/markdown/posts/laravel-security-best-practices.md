---
id: "01KKEW27DTCGJV1A3PWKB1ZTNE"
title: "19 Laravel security best practices for 2025"
slug: "laravel-security-best-practices"
author: "benjamincrozat"
description: "Secure your Laravel app: protect sensitive files, keep your packages and Laravel updated, use policies, validate input, and more."
categories:
  - "laravel"
  - "security"
published_at: 2023-07-29T00:00:00+02:00
modified_at: 2025-07-07T18:00:00+02:00
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/9CsY7RQM6Z45lZr.png"
sponsored_at: null
---
## Introduction

**Security is a broad topic and this article doesn't cover it fully.** Is this even possible anyway?

That being said, I want to give you as much actionable best practices, tips, and tricks to help you consolidate your apps.

**All tips are relevant for Laravel 10, 11, and 12. If you’re not sure whether your Laravel version is still getting security fixes, check [Laravel’s support policy](https://laravel.com/docs/releases#support-policy) before you keep reading.**

## Don't track your .env file

Your *.env* file contains sensitive information.

Please, **don't track it!**
Make sure it's included in your *.gitignore* and not accidentally included in production images or deployments (Docker, anyone?).
Use Docker secrets or AWS Parameter Store for production secrets.

Most of the time, data leaks are inside jobs.
**A password manager is a better solution for sharing credentials.**

If you want your team members to have access to a curated set of sensitive information, use a password manager with a proven track record of rock-solid security.

## Enforce HTTPS and HSTS

No excuse for HTTP in 2025. In `AppServiceProvider`:

```php
use Illuminate\Support\Facades\URL;

public function boot()
{
    if ($this->app->environment('production')) {
        URL::forceScheme('https');
    }
}
```

Set the `Strict-Transport-Security` header in your web server or reverse proxy to ensure browsers never downgrade.

## Keep Laravel up to date

[Keeping Laravel up to date](https://laravel.com/docs/upgrade) allows you to stay in touch with the latest security updates.

**Here’s what you need to know as of July 2025:**

* Laravel 12 is current (bug fixes until Aug 2026, security fixes until Feb 2027)
* Laravel 11 is still in its security-fix window
* If you’re on an older version, you’re on your own

If you never upgraded a Laravel project, [I wrote a guide](https://benjamincrozat.com/laravel-10-upgrade-guide) that will teach you how to do it.
(Yes, upgrading can be a pain, but not upgrading is Russian roulette.)

## Keep your first and third-party packages up to date

Access to dozens of packages from the official Laravel ecosystem and thousands of community packages is what makes our job easier.

But **the more packages you use, the more points of failure** you can be subject to.

Regularly running `composer update` goes a long way toward a more secure codebase. But don’t stop there—add `composer audit` to your routine. This will catch vulnerable dependencies *before* someone else does:

```bash
composer audit --format=table
```

Automate this with Dependabot or Renovate, and only merge if tests + audit pass.

![composer update in action.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/168/conversions/CleanShot_2023-08-07_at_08.00.32_2x_w5c1c1-medium.jpg)

## Disable debug messages in production

Make sure these two environment variables are correctly set in production.

```dotenv
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error
```

You don't want to leak information about your app's architecture or configuration. Usually, debug messages contain a lot of this kind of details.

Remember, `APP_DEBUG=false` disables stack traces in browser, but **your logs might still leak too much info.** Crank `LOG_LEVEL` down to `error` or `critical` in production.

## Don't send sensitive information to error monitoring tools

Talking about sensitive information in debug messages, we haven't eradicated them yet.

If you are using an error monitoring tool, like [Flare](/recommends/flare), you have to hide them there as well.

PHP 8.2 introduced a new attribute, [`\SensitiveParameter`](https://www.php.net/manual/fr/class.sensitiveparameter.php), that can hide anything from the stack trace (which is sent to error monitoring tools).

```php
function something(
    #[\SensitiveParameter]
    $top_secret_parameter,
    $who_cares_parameter,
) {
    throw new \Exception('Whoops, something bad happened!');
}
```

## Restrict parts of your app with policies

[Policies](https://laravel.com/docs/authorization#creating-policies) in Laravel are like nightclub bouncers preventing people from accessing restricted areas.

Here's a real-world example of using a Policy:

```php
// app/Policies/PostPolicy.php
public function update(User $user, Post $post)
{
    return $user->id === $post->user_id;
}

// app/Http/Controllers/PostController.php
public function update(Request $request, Post $post)
{
    $this->authorize('update', $post);

    // ...
}
```

Use policies for every non-trivial access check. It's easy to get lazy—don't.

## Rate limit your endpoints

**Brute force and credential-stuffing attacks are everywhere.**
Add some throttle to your critical routes. Example for logins:

```php
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

RateLimiter::for('login', function ($request) {
    return Limit::perMinute(10)->by($request->ip());
});
```

## Protect your forms from cross-site request forgery (CSRF)

You might want to use the `@csrf` Blade directive in your forms.

```blade
<form method="POST" action="{{ route('register') }}">
    @csrf

    <p>
        <label for="name">First Name</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required />
    </p>

    …
</form>
```

This directive generates a hidden input field containing a CSRF token automatically included when submitting the form.

This token confirms that the form is being submitted from your application and not by a third party.

The verification is handled by the VerifyCsrfToken middleware that Laravel uses by default for all your web routes.

Learn more about [CSRF protection in Laravel's documentation](https://laravel.com/docs/10.x/csrf#main-content).

## Validate the user's input

Validation in Laravel is crucial in ensuring your application's security.

[Validation rules are numerous](https://laravel.com/docs/10.x/validation#available-validation-rules) and will help you sanitize the data your users send with ease. Because you know the drill, right? Never trust your users' input.

```php
use Illuminate\Http\Request;

class PostController extends Controller
{
    function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string|min:3',
            'published' => 'sometimes|boolean'
        ]);

        Post::create($validated);

        //
    }
}
```

In Laravel 12+, `secureValidate()` gives you stricter, safer defaults.

Learn more about [validation in Laravel](https://laravel.com/docs/validation#main-content) on the official documentation.

## Be careful with uploaded files

As we saw, the user's input must never be trusted. That also goes for the files they upload. Here are a few recommendations:

1. Check the file's MIME type (Laravel has the right [validation rules](https://laravel.com/docs/10.x/validation#available-validation-rules) for that).

```php
$request->validate([
    'file' => 'required|mimes:gif,jpeg,png,webp',
]);
```

2. When possible, don't make uploaded files publicly accessible (use the `local` file driver, for instance).
3. Upload files on another server. If a hacker bypasses your securities, they won't be able to run unauthorized code and access sensitive information.
4. Delegate file uploads to a third-party service reputed for its security (meaning they never leaked data).

## Guard against mass-assignment

If you use Eloquent, make sure you’re protecting yourself from mass-assignment attacks. Only allow fields you explicitly trust:

```php
// In your Eloquent model
protected $fillable = ['user_id', 'title', 'content', 'published'];
// Or, better, use form request classes to control what goes in.
```

## Output escaping, XSS & CSP

Cross-site scripting is still everywhere. By default, Blade’s `{{ }}` escapes output. Only use `{!! !!}` when you know what you’re doing.

Want extra peace of mind? Use [spatie/laravel-csp](https://github.com/spatie/laravel-csp) to set a strong Content Security Policy. Sanitize any user HTML with `Purifier::clean()` or similar.

## Secure cookie flags

Set your cookies right in `config/session.php`:

```php
'secure' => true,
'http_only' => true,
'same_site' => 'lax',
```

This helps block session hijacking and CSRF.

## Encrypt the payload of your jobs

Whenever you dispatch a job, its payload is saved into the database, Redis or whatever else you told Laravel to use using the `QUEUE_DRIVER` environment variable.

The payload may contain sensitive information that any of your employee may look at and potentially misuse. As I said in the beginning of this article, leaks are often initiated by employees.

Since Laravel 11, you can enforce global queue encryption by adding this to your `AppServiceProvider`:

```php
use Illuminate\Support\Facades\Queue;

public function boot()
{
    Queue::encrypt();
}
```

Or implement the `Illuminate\Contracts\Queue\ShouldBeEncrypted` Contract for specific jobs:

```php
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;

class SomeJob implements ShouldQueue, ShouldBeEncrypted
{
    //
}
```

To decrypt, the `APP_KEY` in your *.env* must be secure and restricted.

## Multi-factor authentication & passkeys

Passwords aren’t enough.
For APIs, use Laravel Sanctum or Passport with WebAuthn/passkeys.
For web apps, Fortify or Jetstream can set up MFA in no time. In 2025, anything less is reckless.

## Write tests for security risks

[Testing](https://laravel.com/docs/testing) is unfortunately a vast and lesser-known topic among developers.

Automatically testing multiple scenarios for potential security breaches is a great way to make sure they stay closed.

[Laracasts](https://laracasts.com) provides free testing courses to help you get started. One with PHPUnit, the industry standard, and one with [Pest](https://pestphp.com), the best testing framework on this planet that modernizes and simplifies testing in PHP.

* [PHP Testing Jargon](https://laracasts.com/topics/phpunit)
* [Pest From Scratch](https://laracasts.com/topics/pest)

![Keep your project tested](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/169/conversions/CleanShot_2023-07-31_at_20.03.50_2x_hfqpyx-medium.jpg)

Add static analysis (`larastan` or `psalm`) to catch issues early.

## Do regular security audits

This practice is one of the most efficient and should be mandatory for anyone that is really serious about security. External feedback can be eye-opening.

As you can imagine, doing security audits isn't free. It might only be worth it for enterprise since it would cost even more to pay for the potential fines! You cannot put a price on maintaining a good reputation and the trust of your users.

## Bonus: Security.txt & responsible disclosure

Want to look professional and make life easier for white-hats? Add a `/.well-known/security.txt` file pointing to a security contact or bug bounty policy. It’s a tiny effort that can prevent a lot of headaches.

If you are turning this checklist into actual hardening work, these are the next posts I would keep nearby:

- [Fix the 419 error before it keeps breaking forms](/419-page-expired-laravel)
- [Fix the missing app key error before anything else breaks](/laravel-no-application-key-specified)
- [Protect your API with Laravel Sanctum before it gets exposed](/laravel-sanctum-api-tokens-authentication)
- [Decide whether compromised-password checks belong in your auth flow](/block-compromised-password)
- [Pick up Laravel habits that keep projects easier to maintain](/laravel-best-practices)
