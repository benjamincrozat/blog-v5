---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/40/Data_security_24_bxnctl.png
Title: "Here's how to fix the \"419 Page Expired\" error in Laravel"
Description: "Learn how to fix the \"419 Page Expired\" error in Laravel and understand exactly why it happens."
Published at: 2023-06-26
Modified at: 2024-08-30
Categories: laravel, security
---

## Introduction to the "419 Page Expired" error in Laravel

Have you ever encountered the "Page Expired" error with the HTTP code 419 in your Laravel applications?

It's often a simple issue related to CSRF (Cross-Site Request Forgery) tokens.

Let's find out what causes this error and how you can resolve it.

## Why "419 Page Expired" happens and how to fix it

In your Laravel applications, regardless of the version you're running (8, 9, or 10), you have likely used the `@csrf` directive in your forms.

This directive generates a hidden input field containing a CSRF token, which is included when submitting the form.

This token confirms that the form is being submitted from your application and not by a third party.

Errors like the "419 Page Expired" occur when the CSRF token is mismatched. This can happen for various reasons:

- Sometimes, you leave the page open for too long (a login page, for instance), and the token expires, which is good for security. Just click the refresh button in your browser and re-submit the form.
- You might have forgotten to include the `@csrf` directive in your form. This is problematic because, by default, Laravel expects the CSRF token to be present thanks to the `VerifyCsrfToken` middleware that filters the requests.
- The session might have expired, causing the CSRF token to become invalid.
- There could be issues with cookie settings or session configuration.

To fix this error:

1. Always include the `@csrf` directive in your forms.
2. Ensure your session and cookie configurations are correct.
3. If the error persists, try clearing your browser cache and cookies.
4. Check if your web server is properly configured to handle Laravel sessions.

Learn more on Laravel's documentation about [Cross-Site Request Forgery protection](https://laravel.com/docs/10.x/csrf).

## Disable CSRF protection on some pages to avoid the "419 Page Expired" error

Occasionally, you may want to disable CSRF protection on some pages and prevent those "419 Page Expired" errors.

Instead of removing the middleware from the kernel, specify which pages you want to exclude from being protected.

In *app/Http/Middleware/VerifyCsrfToken.php*:
```php
namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/some-page',
        '/some-other-page',
    ];
}
```

