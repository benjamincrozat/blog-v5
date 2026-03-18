---
id: "01KKEW27MCJ5T5D29CNAF1290Q"
title: "PHP redirect: how to redirect to another page"
slug: "php-redirect"
author: "benjamincrozat"
description: "Use header(Location: ...) and exit to redirect in PHP, then choose the right HTTP status code for permanent, temporary, or post-redirect-get flows."
categories:
  - "php"
published_at: 2023-09-02T00:00:00+02:00
modified_at: 2026-03-14T10:12:17Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/q6Lm6JbHGdEbWUB.jpg"
sponsored_at: null
---
## Introduction

To redirect in PHP, send a `Location` header and stop execution with `exit`. That is the core fix. After that, the important decision is choosing the right status code: `301`, `302`, `303`, `307`, or `308`.

## Quick PHP redirect example (TL;DR)

Here's the essential PHP redirect code snippet:

```php
// Start output buffering to avoid "headers already sent" errors.
ob_start();

header('Location: https://example.com', true, 301); // Permanent redirect.

exit;
```

Let's break down what's happening here:

1. **Output buffering (`ob_start()`):** Ensures nothing is sent to the browser prematurely, preventing PHP "headers already sent" errors.
2. **`header()` function:** Directs browsers to the specified URL.
3. **HTTP status code (301):** Indicates this redirect is permanent, beneficial for SEO.
4. **`exit`:** Stops PHP execution immediately after redirection.

## HTTP headers explained

When you redirect with PHP, the server sends an HTTP response like this:

```http
HTTP/1.1 301 Moved Permanently
Location: https://example.com
Content-Type: text/html
```

The important elements:

* **Status code (301):** Signals browsers and search engines this URL has moved permanently.
* **Location header:** Indicates the new URL destination.

Avoid echoing text before sending headers. Output buffering (`ob_start()`) reliably prevents "headers already sent" errors.

## Absolute vs. relative URLs

Always use absolute URLs (`https://example.com/page`) in PHP redirects. Relative URLs (`/page`) may fail with proxies or older browsers, causing unpredictable behavior.

## Choosing the correct HTTP status code

PHP defaults to a `302 Found` redirect if no status code is specified:

```php
header('Location: https://example.com'); // defaults to 302
```

Explicitly choose the correct status code for SEO:

| Status | Meaning                              | Method preserved? | Cache behavior      |
| ------ | ------------------------------------ | ----------------- | ------------------- |
| 301    | Permanent redirect                   | No                | Aggressively cached |
| 302    | Temporary redirect                   | No                | Rarely cached       |
| 303    | Redirect after form submission (PRG) | No                | Not cached          |
| 307    | Temporary redirect                   | Yes               | Temporarily cached  |
| 308    | Permanent redirect                   | Yes               | Aggressively cached |

Example:

```php
// Temporary redirect, preserves POST method.
header('Location: https://example.com/temp-page', true, 307);

exit;
```

### HTTP 308 permanent redirect

The 308 status code preserves the request method (e.g., POST), making it ideal for migrating APIs or services where method consistency matters.

```php
header('Location: https://api.example.com/new-endpoint', true, 308);
exit;
```

## Alternative redirect methods (meta refresh & JavaScript)

Use server-side PHP redirects whenever possible. Meta refresh and JavaScript redirects should only be fallback solutions:

### Meta refresh

Meta refresh redirects occur within HTML tags. Immediate refreshes (`content="0"`) act as permanent redirects; delayed refreshes (`content="5"`) are treated as temporary. Example:

```html
<meta http-equiv="refresh" content="0;url=https://example.com">
```

### JavaScript redirects

JavaScript redirects rely on client-side execution and might not be reliable for all users. They're often slower and can negatively affect SEO. Example:

```javascript
window.location.href = 'https://example.com';
```

## SEO considerations: redirect chains and caching

Googlebot follows a maximum of **10 redirect hops**. Long chains negatively impact SEO and performance. Consolidate redirect chains whenever possible.

Permanent redirects (`301`, `308`) are aggressively cached by browsers and CDNs. For anticipated changes or A/B testing, use temporary codes (`302`, `307`).

Each redirect adds latency, affecting Core Web Vitals, critical to Google's ranking signals in 2025. Optimize redirects to minimize delays.

## Best practices

* Prefer server-level redirects (e.g., `.htaccess`, Nginx).
* Avoid redirect chains and loops.
* Regularly update internal links instead of relying on redirects.
* Monitor redirects with tools like Google Search Console or Screaming Frog.

### Security: avoiding open redirects

Never directly redirect to URLs from user input without validation, risking phishing attacks.

Secure example with URL whitelist:

```php
$allowed_urls = ['/dashboard', '/profile', '/home'];

$next = $_GET['next'] ?? '/home';

if (!in_array($next, $allowed_urls, true)) {
    $next = '/home';
}

header("Location: $next", true, 303);
exit;
```

## Clearer PHP redirection with `http_response_code()`

Explicitly set HTTP status codes for clarity:

```php
ob_start();

http_response_code(301);
header('Location: https://example.com');

exit;
```

## Verifying redirects

Use `curl` to verify redirects from the command line:

```bash
curl -I https://your-site.com/old-page
```

Expected output:

```http
HTTP/1.1 301 Moved Permanently
Location: https://your-site.com/new-page
```

Browser developer tools also show redirect details clearly.

If you are still wiring request and response behavior by hand in PHP, these are the next reads I would keep open:

- [Parse URL paths and query strings without framework helpers](/php-parse-url)
- [Check whether your PHP version is part of the problem](/check-php-version)
- [Find the php.ini file that's actually affecting your setup](/php-ini-location)
