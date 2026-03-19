---
id: "01KKEW27MCJ5T5D29CNAF1290Q"
title: "PHP redirect: how to redirect to another page"
slug: "php-redirect"
author: "benjamincrozat"
description: "Use header('Location: ...') with exit in PHP, then choose the right status code for permanent, temporary, and post-redirect-get redirects."
categories:
  - "php"
published_at: 2023-09-01T22:00:00Z
modified_at: 2026-03-19T22:56:36Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/q6Lm6JbHGdEbWUB.jpg"
sponsored_at: null
---
## Introduction

To redirect in PHP, send a `Location` header and stop the script immediately with `exit`.

```php
header('Location: /dashboard', true, 302);
exit;
```

That is the whole pattern. The only real decisions are:

- which URL to redirect to
- which HTTP status code to send
- how to avoid sending output before the redirect

## The quickest PHP redirect recipes

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

Always stop execution after the redirect:

```php
header('Location: /dashboard');
exit;
```

Without `exit`, the rest of the script can keep running. That can lead to:

- extra output being sent accidentally
- database writes continuing after a redirect
- confusing bugs when protected code still executes

## Which redirect status code should you use?

PHP sends `302 Found` by default if you omit the status code:

```php
header('Location: /login');
exit;
```

That is fine for many cases, but it is better to choose intentionally.

| Status | Use it when | Method preserved? |
| --- | --- | --- |
| `301` | The move is permanent | No |
| `302` | The move is temporary | No |
| `303` | You are redirecting after a POST | No, next request becomes `GET` |
| `307` | Temporary redirect and the request method must stay the same | Yes |
| `308` | Permanent redirect and the request method must stay the same | Yes |

### 303 vs 307 vs 308

This is where most confusion happens:

- `303` is best after form submissions
- `307` keeps the original method and body, so a `POST` stays a `POST`
- `308` is the permanent version of `307`

For normal page redirects, `301`, `302`, and `303` are usually enough.

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
