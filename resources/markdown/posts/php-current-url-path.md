---
id: "01KKEW27KB8NBE20H59WB406Y1"
title: "Get the current URL path in PHP"
slug: "php-current-url-path"
author: "benjamincrozat"
description: "Discover how to fetch the current URL path in PHP thanks to an useful superglobal variable."
categories:
  - "php"
published_at: 2023-09-03T00:00:00+02:00
modified_at: 2025-07-06T09:27:00+02:00
serp_title: "Get the current URL path in PHP (2025)"
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/pQGjDTu26ZnnLEj.jpg"
sponsored_at: null
---
## TD;DR

To grab the current URL path in PHP, Here’s a dead-simple way:

```php
echo $_SERVER['REQUEST_URI'];
```

Done. That’ll spit out the path plus any query string. For example, if your URL is
`https://www.example.com/foo?bar=baz`, you’ll get `/foo?bar=baz`.

But let’s not stop at the basics. Here’s what you need to know to *really* work with URL paths in PHP.

## Strip the query string

A lot of the time, you only want the path itself (think: routing, breadcrumbs, active menu). Use [`parse_url`](https://www.php.net/parse_url):

```php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);   // Gives you /foo
$query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY); // Gives you bar=baz
```

Now you can compare, match, or split up routes without worrying about random query parameters.

## Never trust raw output

This is web dev 101, but people still forget it: **Never echo user-supplied URLs straight into your HTML**.
Why? Because proxies and malicious clients can send absolute garbage, even in the path.

Here’s how to avoid headaches (or a security breach):

```php
echo htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
```

## Edge cases nobody talks about

Getting the current path seems dead simple, but in the real world, PHP doesn’t always behave the same everywhere. Let’s break down the common weird situations—**with real examples**:

### 1. Running from the CLI (Command Line Interface)

If you’re running your PHP script from the terminal (not through a web server), `$_SERVER['REQUEST_URI']` just isn’t there.

**Example:**

```sh
php myscript.php
```

`$_SERVER['REQUEST_URI']` is **undefined**. If you try to use it, you’ll get an error or a warning. So, before using `$_SERVER['REQUEST_URI']`, check if it’s actually set:

```php
if (isset($_SERVER['REQUEST_URI'])) {
    // Safe to use.
}
```

This will save you a headache if you ever automate or test your PHP scripts.

### 2. Behind a reverse proxy or load balancer

A lot of production sites run behind a proxy (think: Cloudflare, Nginx in front of Apache, load balancers).
Sometimes these proxies rewrite the request before it hits PHP. So, the URI your PHP app sees *might not* be what the user actually typed in the browser.

**Example:**

* User visits: `https://mycoolsite.com/app/foo`
* Proxy strips `/app`, forwards to PHP as `/foo`
* `$_SERVER['REQUEST_URI']` = `/foo` (not `/app/foo`)

Some proxies add special headers like `X-Original-URI` or `X-Forwarded-Uri` that show the *true* path.
**But:** PHP doesn’t handle these automatically. You have to check these headers yourself if you care.

### 3. **URL rewrites and front controllers**

Most frameworks (and modern PHP apps) use a “front controller” pattern—every request goes through a single file, like `index.php`, thanks to `.htaccess` or Nginx rewrite rules.

**Example:** User visits: `/about/contact`
* Apache rewrites it as `/index.php`
* PHP receives `$_SERVER['REQUEST_URI']` = `/about/contact`
* But `$_SERVER['PHP_SELF']` = `/index.php`

### 4. **PATH\_INFO and “pretty URLs”**

If you use “pretty URLs” in old-school style (like `/index.php/foo/bar`), PHP can fill out `$_SERVER['PATH_INFO']` as `/foo/bar`.

But not every server/setup does this by default. Sometimes it’s empty. Stick to `REQUEST_URI` unless you’re specifically using PATH\_INFO routing.

### 5. **Unexpected encoding or special characters**

URLs can have all kinds of weird stuff—spaces, accented letters, emoji, even percent-encoded junk.

**Example:** User visits: `/café?utm=étoile`. `$_SERVER['REQUEST_URI']` gives `/caf%C3%A9?utm=%C3%A9toile`.

If you show that directly to users, it’s ugly.
But decoding it with [`urldecode()`](https://www.php.net/urldecode) can cause issues if you don’t handle character sets correctly.

## The other PHP variables (and why you probably don’t want them)

* `$_SERVER['PHP_SELF']` – Gives you the path to the currently executing script (often `/index.php`). Not the same as the current request path after your .htaccess or Nginx rewrites.
* `$_SERVER['PATH_INFO']` – Only set if you’re using “pretty URLs” with path info after the script filename (`/index.php/foo/bar`).

In 99% of modern setups, just use `REQUEST_URI`.

## What about weird characters in URLs?

PHP’s `REQUEST_URI` is percent-encoded by default. So if you’ve got non-English slugs, you’ll see stuff like `/déjà-vu` as `/d%C3%A9j%C3%A0-vu`. If you need to show these to users, don’t blindly use `urldecode()`—sometimes it’ll break multibyte strings. For most cases, just leave it as-is or use `rawurldecode()` with care.

## A reusable helper (because you’ll do this more than once)

Here’s a quick function you can drop into your codebase:

```php
function current_path(bool $with_query = false): string
{
    $uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL) ?? '/';
    return $with_query ? $uri : parse_url($uri, PHP_URL_PATH);
}
```

Usage:

```php
echo current_path();     // Just the path.
echo current_path(true); // Path + query string.
```

## Where does this come in handy?

* **Breadcrumbs:** Use the current path to build out a clickable trail.
* **Active nav states:** Dynamically highlight the current page.
* **Redirection:** Send users back to where they started.
* **Logging:** Know exactly which URL triggered that weird bug in production.

If you’re using a full-blown framework (Laravel, Symfony, Slim, etc.), their routers probably give you cleaner ways to access the path. But for vanilla PHP or legacy codebases, this is what you want.

If you want a few more PHP rabbit holes after this:

- [PHP for Mac: get started fast using Laravel Valet](/laravel-valet)
- [6 ways to check your version of PHP](/check-php-version)
- [PHP: Show all errors (E_ALL) safely](/php-show-all-errors)

