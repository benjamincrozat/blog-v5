---
id: "01KKEW27DTCGJV1A3PWKB1ZTNE"
title: "16 Laravel security best practices for 2026"
slug: "laravel-security-best-practices"
author: "benjamincrozat"
description: "Secure Laravel apps with current support windows, secret handling, HTTPS, auth, validation, safe uploads, secure cookies, and security-focused testing."
categories:
  - "laravel"
  - "security"
published_at: 2023-07-28T22:00:00Z
modified_at: 2026-03-12T21:37:08Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/9CsY7RQM6Z45lZr.png"
sponsored_at: null
---
## Introduction

Laravel gives you strong security defaults, but defaults only help when you keep them enabled and avoid bypassing them under pressure.

This guide focuses on the habits that matter most in real projects: supported framework versions, secret handling, authentication, authorization, validation, uploads, headers, sessions, and tests.

One date-sensitive reminder first: **as of March 12, 2026, Laravel 11 is on its last day of security support, and Laravel 12 remains supported until February 24, 2027**, according to [Laravel's support policy](https://laravel.com/docs/12.x/releases#support-policy).

For a broader framework-specific checklist, OWASP's [Laravel cheat sheet](https://cheatsheetseries.owasp.org/cheatsheets/Laravel_Cheat_Sheet.html) is also worth bookmarking.

## 1. Keep Laravel and your packages within supported windows

Security advice starts with the least glamorous rule on the list: stay current.

If your framework version is out of support, security patches stop arriving. The same is true for abandoned packages.

A practical baseline:

- keep Laravel on a supported release
- update first-party and third-party packages regularly
- run `composer audit` in CI
- use Dependabot or Renovate so outdated dependencies stop being invisible

```bash
composer audit --format=table
```

If you are still on Laravel 11, March 12, 2026 is not "roughly when support ends." It is the final day of its security support window.

## 2. Keep secrets out of git, containers, and logs

Never commit `.env`, and never assume "private repo" means "safe enough."

Your secrets can also leak through:

- Docker images
- CI logs
- job payloads
- support screenshots
- copied `.env` files in shared chat threads

Use a secrets manager or a password manager for distribution, and treat a leaked `APP_KEY`, database password, or API token as an incident that requires rotation, not a cleanup chore for later.

If the `APP_KEY` leaks, rotate it deliberately and plan for the impact on encrypted values and sessions.

## 3. Enforce HTTPS everywhere and set HSTS

Terminate TLS at your web server or proxy, redirect plain HTTP to HTTPS, and set `Strict-Transport-Security` so browsers stop attempting insecure downgrade requests.

Laravel can help at the application layer, but the primary enforcement should happen at the edge.

If you are behind a proxy or load balancer, make sure Laravel is correctly configured to trust it. Otherwise, secure URLs, redirects, and cookies can behave incorrectly even when HTTPS is technically enabled.

## 4. Disable debug mode in production

Production should not expose stack traces, environment details, or internal paths to end users.

At minimum:

```dotenv
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error
```

`APP_DEBUG=false` is not the whole story, though. You should also review what your logs, exception pages, and monitoring tools capture, because a safe browser response can still be paired with an unsafe log trail.

## 5. Hide sensitive values from error reports

Laravel apps often send exceptions to tools like Flare or Sentry. That is useful, but only if you keep secrets out of the payload.

PHP's [`\SensitiveParameter`](https://www.php.net/manual/en/class.sensitiveparameter.php) attribute is an easy win here:

```php
function authenticate(
    string $email,
    #[\SensitiveParameter] string $password,
): void {
    // ...
}
```

Also review your error monitoring configuration so tokens, cookies, authorization headers, and personal data are scrubbed before they leave your infrastructure.

## 6. Let Laravel hash passwords and handle auth primitives

Do not invent your own password hashing.

Laravel's [hashing layer](https://laravel.com/docs/12.x/hashing) already gives you the right primitives for password storage and verification.

Use:

- `Hash::make()` to create hashes
- `Hash::check()` to verify them
- Laravel's auth packages or starter kits instead of rolling your own login flow

This is also a good place to harden sign-in itself. If you want an extra safeguard against weak credentials, my guide on [blocking compromised passwords in Laravel](/block-compromised-password) is a good next step.

## 7. Centralize authorization with policies and gates

Authentication answers "who is this?" Authorization answers "what can they do?"

Laravel's [policies](https://laravel.com/docs/12.x/authorization#creating-policies) and gates give you a central place for those decisions.

```php
public function update(User $user, Post $post): bool
{
    return $user->id === $post->user_id;
}
```

That is safer than sprinkling ad hoc ownership checks across controllers, jobs, Livewire components, and API endpoints.

If an action is sensitive, treat the authorization rule as part of the feature, not as optional cleanup.

## 8. Rate limit login, reset, and token endpoints

Brute-force and credential-stuffing attacks are routine, not hypothetical.

Laravel supports [rate limiting](https://laravel.com/docs/12.x/routing#rate-limiting), so use it for:

- login endpoints
- password reset endpoints
- token issuance endpoints
- any route that can be abused cheaply

```php
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

RateLimiter::for('login', function (Request $request): Limit {
    return Limit::perMinute(5)->by($request->ip());
});
```

If you are exposing an API, pair this with good token practices. I cover that in the dedicated [Laravel Sanctum authentication guide](/laravel-sanctum-api-tokens-authentication).

## 9. Keep CSRF protection enabled for stateful web routes

Laravel's [CSRF protection](https://laravel.com/docs/12.x/csrf) is one of the framework defaults you should be very slow to disable.

For Blade forms, use `@csrf` and leave the middleware in place for your web routes.

```blade
<form method="POST" action="{{ route('profile.update') }}">
    @csrf

    <!-- fields -->
</form>
```

CSRF is usually *not* what you want on stateless API routes that use tokens instead of browser cookies. But for web routes, do not casually add CSRF exceptions because something broke once. Fix the root cause instead.

If your users are seeing 419 errors, this  [Laravel 419 guide](/419-page-expired-laravel) explains the usual causes before you start disabling protections you still need.

## 10. Validate input and never mass-assign blindly

Security and validation are tightly linked.

Use Laravel's [validation system](https://laravel.com/docs/12.x/validation) or form requests to define what is allowed, then persist only validated data.

```php
Post::create($request->validated());
```

Two practical rules matter here:

- validate every public input surface
- never trust user-supplied fields like `user_id`, `role`, or `is_admin` just because they passed through a controller

Mass-assignment protection still matters too. Keep your models explicit with `$fillable` or carefully chosen guarded behavior, and do not pass untrusted request blobs straight into `create()` or `update()`.

## 11. Treat file uploads as hostile input

Files deserve their own security mindset.

Laravel's [file validation rules](https://laravel.com/docs/12.x/validation#validating-files) help, but validation is only the first layer.

```php
use Illuminate\Validation\Rules\File;

$request->validate([
    'avatar' => ['required', File::image()->max(5 * 1024)],
]);
```

Also:

- store uploads outside the public web root when possible
- generate storage names instead of trusting the original filename
- keep executable files off disks that can serve them directly
- scan or offload uploads when the risk profile justifies it

If you handle uploads from untrusted users, assume someone will eventually try to weaponize them.

## 12. Escape output and add security headers

Blade escapes `{{ }}` output by default, which is one of Laravel's most valuable protections against cross-site scripting.

That protection disappears when you render raw HTML with `{!! !!}` or trust user HTML without sanitizing it first.

On top of output escaping, add security headers where they help:

- `Content-Security-Policy`
- `X-Frame-Options`
- `X-Content-Type-Options`
- `Referrer-Policy`

The OWASP cheat sheet has a good section on headers and browser-facing hardening if you want to go deeper.

## 13. Use secure session and cookie settings

Session cookies should be hard to steal and hard to misuse.

In `config/session.php`, review at least these values:

```php
'secure' => true,
'http_only' => true,
'same_site' => 'lax',
```

These flags help reduce session hijacking and CSRF exposure, especially when combined with proper HTTPS and proxy configuration.

Cookie and session settings are easy to forget because they rarely get revisited after the first deploy. That is exactly why they deserve an explicit check.

## 14. Encrypt queued work that carries secrets

Queued jobs, listeners, notifications, and mailables can carry sensitive data.

Laravel supports encrypting queued payloads via the `ShouldBeEncrypted` contract. Use it when the payload includes secrets, personal data, or anything you would not want casually visible in Redis or the database queue table.

```php
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;

final class ExportCustomerData implements ShouldQueue, ShouldBeEncrypted
{
    // ...
}
```

This does not remove the need to protect your queue backend, but it does reduce the blast radius if someone can inspect payloads.

## 15. Add MFA for high-risk accounts

Passwords alone are not enough for every surface.

For admin panels, billing areas, customer data access, or anything else with real account impact, add multi-factor authentication.

Laravel's auth ecosystem gives you multiple ways to do that. Jetstream and Fortify cover common two-factor flows, and Laravel 12's WorkOS AuthKit starter kit path is worth evaluating if you need passkeys or enterprise auth features.

Do not wait for a breach to decide which accounts were "important enough."

## 16. Test, audit, and publish a disclosure path

Security hardening should be checked, not assumed.

That means:

- feature tests for authorization and validation rules
- checks for rate limits and auth boundaries
- static analysis and dependency auditing in CI
- periodic external security review when the app's risk justifies it

It also means giving responsible reporters a way to reach you. A simple `/.well-known/security.txt` file with a security contact is a small step that can prevent unnecessary chaos.

If you want to harden security changes with better regression coverage, my [Laravel testing best practices](/laravel-testing-best-practices) guide is the next read.

## FAQ

### Does Laravel handle security automatically?

Laravel gives you good defaults, not automatic immunity. You still need supported versions, secure infrastructure, correct configuration, and discipline around auth, validation, logging, and secrets.

### Should API routes use CSRF protection?

Usually no for stateless token-based APIs, yes for stateful web routes that rely on browser cookies. The important part is understanding which authentication model the route is using.

### What if the `APP_KEY` leaks?

Treat it like a real incident. Rotate it deliberately, assess what encrypted data and sessions are affected, and rotate any other secrets that may have leaked with it.

### Sanctum or Passport for API security?

Use Sanctum by default unless you clearly need OAuth2-style delegated authorization or third-party client flows. I go deeper in the dedicated Sanctum article linked above.

## Conclusion

Laravel security is mostly about not defeating the framework's good defaults.

Stay on supported versions, keep secrets out of the wrong places, use Laravel's auth and authorization primitives, validate and escape aggressively, treat uploads as hostile, and keep testing the boundaries that matter. That will get you much further than panic-driven "security cleanup" after something breaks.

If you are turning this checklist into actual hardening work, these are the next posts I would keep nearby:

- [Protect your API with Laravel Sanctum before it gets wider exposure](/laravel-sanctum-api-tokens-authentication)
- [Decide whether compromised-password checks belong in your auth flow](/block-compromised-password)
- [Fix 419 form failures without ripping out CSRF protection](/419-page-expired-laravel)
- [Catch auth and validation regressions with stronger Laravel tests](/laravel-testing-best-practices)
- [Pick up the Laravel habits that make security work easier to maintain](/laravel-best-practices)
