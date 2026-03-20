---
id: "01KKEW27MCJ5T5D29CNAF1290Q"
title: "PHP redirect: how to send users to another page"
slug: "php-redirect"
author: "benjamincrozat"
description: "Use header('Location: ...') with exit in PHP, then choose the right status code for permanent, temporary, and form redirects."
categories:
  - "php"
published_at: 2023-09-01T22:00:00Z
modified_at: 2026-03-20T09:46:02Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/q6Lm6JbHGdEbWUB.jpg"
sponsored_at: null
---
## Quick answer

To redirect in PHP, send a `Location` header and stop the script immediately with `exit`. PHP's [`header()` manual](https://www.php.net/manual/en/function.header.php) notes that `Location` defaults to `302` unless you already set a `3xx` status code, so choose the right one on purpose.

```php
header('Location: /dashboard', true, 302);
exit;
```

That is the whole pattern. The only real decisions are:

- where the user should land
- whether the move is permanent or temporary
- whether the next request must keep the same HTTP method

## The fastest redirect recipes

### Temporary redirect

Use `302` when the target may change again.

```php
header('Location: /login', true, 302);
exit;
```

### Permanent redirect

Use `301` when an old URL has moved for good.

```php
header('Location: /new-url', true, 301);
exit;
```

### Redirect after a form submission

Use `303` after handling a POST request so the next request becomes a normal `GET`.

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save data...

    header('Location: /thanks', true, 303);
    exit;
}
```

This is the standard Post/Redirect/Get flow.

## Why `exit` matters

`header('Location: ...')` sends the redirect response, but it does not stop PHP from running the rest of the file. `exit` is what actually ends the request.

```php
header('Location: /dashboard');
exit;
```

Without `exit`, the rest of the script can keep running. That can lead to:

- extra output being sent accidentally
- database writes continuing after a redirect
- confusing bugs when protected code still executes

## Which redirect status code should you use?

Use the smallest code that matches the intent:

- `301` when the move is permanent
- `302` when the move is temporary
- [`303 See Other`](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/303) when a `POST` should finish on a `GET` page
- `307` when the move is temporary but the method must stay the same
- `308` when the move is permanent and the method must stay the same

If you want the short rule of thumb: use `301` for permanent URL changes, `302` for temporary navigation, and `303` after form submissions.

## Relative vs absolute URLs

Both relative and absolute URLs work with `Location` headers in modern PHP setups.

```php
header('Location: /pricing', true, 302);
exit;
```

```php
header('Location: https://example.com/pricing', true, 302);
exit;
```

Use absolute URLs when you are redirecting to another domain. For same-site redirects, relative paths are usually simpler and easier to move between environments.

## Common PHP redirect mistakes

### Output sent before `header()`

This is the classic `"Cannot modify header information - headers already sent"` error.

```php
echo 'Hello';

header('Location: /dashboard'); // Too late
exit;
```

Common causes:

- `echo` or `print` before the redirect
- stray whitespace before `<?php`
- included files that already sent output
- UTF-8 BOM issues in older files

If you are debugging this, [`headers_sent()`](https://www.php.net/manual/en/function.headers-sent.php) can help you find where output started.

### Forgetting `exit`

This is the second most common mistake:

```php
header('Location: /dashboard');

// This code still runs if you forget exit
```

### Using user input directly

Never redirect to a raw `$_GET['next']` value without validation. That creates an open redirect risk.

```php
$allowedPaths = ['/dashboard', '/settings', '/billing'];
$next = $_GET['next'] ?? '/dashboard';

if (!in_array($next, $allowedPaths, true)) {
    $next = '/dashboard';
}

header("Location: $next", true, 303);
exit;
```

If you need to inspect or normalize a redirect target first, [Parse URL paths and query strings without framework helpers](/php-parse-url) is the safer companion read.

## When PHP redirects are the wrong tool

For one-off request handling inside an app, PHP redirects are fine.

For large URL migrations, domain moves, or many SEO redirects, handle them at the web server or CDN layer when possible. That is usually faster and easier to maintain than scattering redirect logic through PHP files.

## How to verify a redirect

Use `curl -I` to inspect the response headers:

```bash
curl -I https://example.com/old-url
```

Example response:

```http
HTTP/1.1 301 Moved Permanently
Location: https://example.com/new-url
```

That confirms both the status code and the redirect target.

## FAQ

### Do I need `ob_start()` before every PHP redirect?

No. It can help in some debugging situations, but it should not be the default fix. The better fix is to avoid sending output before `header()`.

### Does `header('Location: ...')` stop PHP automatically?

No. You still need `exit` or `die`.

### What redirect code is best for SEO?

Use `301` for permanent moves. Use `302` or `307` when the change is temporary.

### What code should I use after a POST form submission?

Use `303 See Other`.

## Conclusion

The standard PHP redirect pattern is small: send the `Location` header, choose the right status code, and call `exit`. If you remember one thing, remember the `303` pattern for forms and the `301` pattern for permanent URL changes. The rest is mostly about avoiding output-before-header bugs and validating redirect targets safely.

If you are still wiring request handling by hand in PHP, these are the next reads I would keep open:

- [Parse URL paths and query strings without framework helpers](/php-parse-url)
- [Show every PHP error when debugging gets vague](/php-show-all-errors)
- [Find the php.ini file that's actually affecting your setup](/php-ini-location)
