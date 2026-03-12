---
id: "01KKEW275Z8QCA298G8Z5CAEC5"
title: "The \"419 Page Expired\" error in Laravel: 5 quick fixes"
slug: "419-page-expired-laravel"
author: "benjamincrozat"
description: "Stop the “419 Page Expired” error in Laravel. Learn why the CSRF token fails and the exact code tweaks to fix this."
categories:
  - "laravel"
  - "security"
published_at: 2023-06-26T00:00:00+02:00
modified_at: 2025-09-16T03:26:00+02:00
serp_title: "The \"419 Page Expired\" error in Laravel: 5 quick fixes (2025)"
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K29KAP8DWT3PCD8NPJ5AYEPE.png"
sponsored_at: null
---
## Introduction

Ever hit the frustrating “419 Page Expired” error in your Laravel app? It commonly disrupts form submissions, logins, and other POST requests tied to CSRF protection and session state. I regularly diagnose 419s for teams, and the checklist below resolves most cases quickly.

Note: **419 Page Expired is a non‑standard status code** that Laravel uses for CSRF token mismatches or expired/rotated sessions; it is not an IANA HTTP status. See the [list of HTTP status codes](https://en.wikipedia.org/wiki/List_of_HTTP_status_codes) for context.

### TL;DR: five quick fixes

1) Ensure every form includes `@csrf`.
2) If you were idle, refresh the page to get a fresh session token; this works as designed.
3) For AJAX, make sure the request sends `X-CSRF-TOKEN` or `X-XSRF-TOKEN`. Axios often handles this automatically on same‑origin requests.
4) Verify session and cookie config: lifetime, `same_site`, `secure`, and `SESSION_DOMAIN` aligned to your domain scheme.
5) If needed for webhooks, exclude routes from CSRF checks using `validateCsrfTokens` in Laravel 11. Details below.

Quick checklist (snippet‑friendly): check `@csrf`, then your session driver, `SESSION_DOMAIN`, and whether cookies are sticking across requests. This mirrors common advice in the long‑running [Stack Overflow thread](https://stackoverflow.com/questions/52583886/post-request-in-laravel-error-419-sorry-your-session-419-your-page-has-exp). See the [troubleshooting checklist](#troubleshooting-checklist) for a deeper pass.

## Why a 419 happens in Laravel

### CSRF token and session basics

Laravel generates a CSRF token per user session and verifies it on state‑changing requests. The token is tied to the session; a so‑called “token expired” situation usually means the session expired or was regenerated, which causes a mismatch until the page is refreshed. See Laravel’s [CSRF documentation](https://laravel.com/docs/csrf).

### Common root causes

- Missing `@csrf` on forms.
- Session expired or regenerated.
- `XSRF-TOKEN` cookie present but the request header is missing on AJAX.
- `SESSION_DOMAIN` misconfigured or cross‑subdomain usage without a proper cookie domain.
- Mixing HTTPS and HTTP between page load and request.

## Fixes in detail

### Forms

Always include the directive in Blade forms:

```html
<form method="POST" action="/submit">
  @csrf
  <input type="text" name="name" />
  <button type="submit">Send</button>
</form>
```

If users step away for a while and the session expires or rotates, ask them to refresh the page to get a fresh token; it works as designed.

### AJAX and SPAs with Sanctum

#### Handling AJAX requests properly

Laravel sets an `XSRF-TOKEN` cookie, and many libraries automatically send `X-XSRF-TOKEN` on same‑origin requests. You can also send the token explicitly with `X-CSRF-TOKEN` from a meta tag.

```html
<meta name="csrf-token" content="{{ csrf_token() }}" />
```

```javascript
fetch('/submit', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
  },
  body: JSON.stringify({ example: 1 })
});
```

Notes for Axios/jQuery users:
- On same‑origin requests, Axios typically reads `XSRF-TOKEN` and sends `X-XSRF-TOKEN` automatically; jQuery can do similar with appropriate setup. You usually do not need to wire this header manually.
- If a library expects a decoded value, URL‑decode the cookie before sending (for example, `decodeURIComponent`). See [Laravel CSRF docs](https://laravel.com/docs/csrf).

#### SPAs with Sanctum

For SPA authentication with cookies, initialize CSRF protection, then log in. Ensure cookie domain and SameSite settings match your architecture. Axios can send the header automatically on same‑origin requests.

```javascript
// Initialize CSRF protection, then perform login
await axios.get('/sanctum/csrf-cookie');
await axios.post('/login', { email, password });
```

If your SPA is on a different subdomain, enable credentials and set cookie/domain config accordingly:

```javascript
axios.defaults.withCredentials = true;
```

See [Laravel Sanctum: SPA authentication](https://laravel.com/docs/sanctum) and the [CSRF docs](https://laravel.com/docs/csrf).

### Session and cookie configuration

Check reasonable defaults in `config/session.php` and align them to your environment:

```php
'lifetime' => env('SESSION_LIFETIME', 120), // minutes
'expire_on_close' => false,
'http_only' => true, // HttpOnly
'secure' => env('SESSION_SECURE_COOKIE', true),
'same_site' => env('SESSION_SAME_SITE', 'lax'), // 'lax', 'strict', or 'none'
'domain' => env('SESSION_DOMAIN', null),
```

Practical notes:
- If `SameSite` is set to `none`, the cookie must also be `Secure` and the site must be served over HTTPS. Browsers otherwise reject the cookie. See this reminder and discussion in community answers and docs. 
- Avoid setting `SESSION_DOMAIN` to an IP or a mismatched domain during development; leave it `null` unless you specifically need cross‑subdomain sharing. Browsers treat the cookie Domain attribute strictly. See MDN’s overview of [cookies](https://developer.mozilla.org/en-US/docs/Web/HTTP/Cookies).

### Server configuration for Apache and Nginx

Correct web server routing prevents Laravel from bypassing `public/index.php`.

Apache: set your VirtualHost `DocumentRoot` to the application’s `public` directory and use Laravel’s default `public/.htaccess` with file/dir checks. Minimal rewrite section:

```apache
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule ^ index.php [L]
</IfModule>
```

Nginx: a minimal, copy‑pasteable server block that points `root` to `/public`, uses `try_files`, and forwards PHP to FPM:

```nginx
server {
  listen 80;
  server_name example.com;
  root /var/www/your-app/public;
  index index.php index.html;

  location / {
    try_files $uri $uri/ /index.php?$query_string;
  }

  location ~ \.php$ {
    include fastcgi_params;
    fastcgi_pass unix:/run/php/php8.2-fpm.sock; # adjust to your PHP-FPM socket or host:port
    fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
  }

  location ~ /\.[^/]+ { deny all; }
}
```

These patterns mirror widely used examples from community guides and the default Laravel `.htaccess`.

## Laravel 11 vs 10 and earlier: excluding routes from CSRF

When you must skip CSRF verification for specific endpoints like webhooks, prefer a targeted exclude.

Laravel 11:

```php
// bootstrap/app.php
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
  ->withMiddleware(function (Middleware $middleware) {
      $middleware->validateCsrfTokens(except: [
          'webhook/*',
      ]);
  })
  ->create();
```

Laravel 10 and earlier:

```php
// app/Http/Middleware/VerifyCsrfToken.php
class VerifyCsrfToken extends Middleware
{
    protected $except = [
        'webhook/*',
    ];
}
```

See Laravel’s [CSRF docs](https://laravel.com/docs/csrf) for details.

## Troubleshooting checklist

- Forms: `@csrf` present in every POST/PUT/PATCH/DELETE form; method spoofing is correct.
- Session driver: confirm a stable driver (`file`, `redis`, etc.) and that sessions persist across requests.
- Cookies: verify `XSRF-TOKEN` and `LARAVEL_SESSION` originate from the correct domain and path; confirm `Secure` and `SameSite` fit your setup.
- AJAX: confirm your request includes `X-CSRF-TOKEN` or `X-XSRF-TOKEN` and that the browser is sending cookies.
- Domains: avoid `SESSION_DOMAIN` mismatches and IP‑based domains that prevent cookies from sticking.
- Protocol: do not mix HTTP and HTTPS between page load and API calls.

## Additional troubleshooting tips

- Use Artisan to clear build‑time caches that can confuse local testing:
  ```shell
  php artisan optimize:clear
  php artisan cache:clear
  php artisan config:clear
  php artisan view:clear
  ```
- Quick server‑side session sanity check:
  ```php
  Route::get('/session-test', function () {
      session(['test' => 'it works!']);
      return session('test');
  });
  ```
- Review Laravel’s logs in `storage/logs/laravel.log`.

## FAQ

- Is 419 a real HTTP status code?
  No. It is a non‑standard status used by Laravel to indicate a CSRF mismatch or expired session. See the [HTTP status code list](https://en.wikipedia.org/wiki/List_of_HTTP_status_codes).

- How do I fix 419 on Axios or fetch?
  For same‑origin requests, Axios usually sends `X-XSRF-TOKEN` automatically from the `XSRF-TOKEN` cookie. Otherwise, set `X-CSRF-TOKEN` from a meta tag or decode and send the cookie yourself. For cross‑subdomain with Sanctum, call `/sanctum/csrf-cookie` and enable credentials. See [Laravel CSRF](https://laravel.com/docs/csrf) and [Sanctum](https://laravel.com/docs/sanctum).

- How do I disable CSRF for webhooks in Laravel 11?
  Use `validateCsrfTokens(except: [...])` in `bootstrap/app.php`. See the code and the [CSRF docs](https://laravel.com/docs/csrf).

- Why do I get 419 after being idle?
  Your session likely expired or was regenerated, which invalidates the token until you refresh the page. See [CSRF protection](https://laravel.com/docs/csrf).

- Why does 419 happen on subdomains or IPs?
  Cookies may not be sent if `SESSION_DOMAIN` is misconfigured or if you are testing on an IP where cookie domain rules differ. Browsers enforce cookie domain attributes strictly. See MDN on [cookies](https://developer.mozilla.org/en-US/docs/Web/HTTP/Cookies).

- Why does the console show “Unknown status 419”?
  Some tools label non‑standard statuses as “Unknown.” It is still the same CSRF/session issue described here.

- Do I need both `X-CSRF-TOKEN` and `X-XSRF-TOKEN`?
  No. Send one: either `X-CSRF-TOKEN` from a meta tag or `X-XSRF-TOKEN` derived from the `XSRF-TOKEN` cookie. See [Laravel CSRF docs](https://laravel.com/docs/csrf).

## Conclusion

The Laravel 419 error is almost always a solvable CSRF or session configuration issue. Start with `@csrf`, refresh if idle, confirm the AJAX header, validate session and cookie settings, and only then consider route exclusions. My preferred order is forms → AJAX headers → session and cookie settings → server routing → targeted CSRF excludes. For deeper reference, see Laravel’s official [CSRF protection documentation](https://laravel.com/docs/csrf) and [Sanctum docs](https://laravel.com/docs/sanctum).

If you are still tightening forms, sessions, and API requests after the 419 fix, these are the next posts I would open:

- [Close the Laravel security gaps that are easy to miss](/laravel-security-best-practices)
- [Protect your API with Laravel Sanctum before it gets exposed](/laravel-sanctum-api-tokens-authentication)
- [Fix "No application encryption key has been specified." in Laravel](/laravel-no-application-key-specified)
- [Adjust Laravel 11 middleware without hunting through the framework](/customize-middleware-laravel-11)
- [Write validation rules with less guesswork](/laravel-validation)
