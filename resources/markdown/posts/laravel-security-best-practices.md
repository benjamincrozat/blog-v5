---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/48/dhjbi100_uiarfm.png
Title: "12 Laravel security best practices for 2024"
Description: "Secure your Laravel app: protect sensitive files, keep your packages and Laravel updated, use policies, validate input, and more."
Published at: 2023-07-29
Modified at: 2024-01-21
Categories: laravel, security
---

## Introduction

**Security is a broad topic and this article doesn't cover it fully.** Is this even possible anyway?

That being said, I want to give you as much actionable best practices, tips and tricks to help you consolidate your Laravel web applications.

## Don't track your .env file

Your *.env* file contains sensitive information. 

Please, **don't track it!**

Make sure it's included in your *.gitignore*.

Most of the time, data leaks are inside jobs.

**A password manager is a better solution for sharing credentials.**

If you want your team members to have access to a curated set of sensitive information, use a password manager with a proven track record of rock-solid security.

## Keep Laravel up to date

[Keeping Laravel up to date](https://laravel.com/docs/upgrade) allows you to stay in touch with the latest security updates.

Make sure you are running a version of Laravel that is still being patched, and you should be OK.

If you never upgraded a Laravel project, [I wrote a guide](https://benjamincrozat.com/laravel-10-upgrade-guide) that will teach you how to do it.

## Keep your first and third-party packages up to date

Access to dozens of packages from the official Laravel ecosystem and thousands of community packages is what makes our job easier.

But **the more packages you use, the more points of failure** you can be subject to.

Regularly running `composer update` goes a long way toward a more secure codebase.

![composer update in action.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/168/conversions/CleanShot_2023-08-07_at_08.00.32_2x_w5c1c1-medium.jpg)

## Disable debug messages in production

Make sure these two environment variables are correctly set in production.

```
APP_ENV=production
APP_DEBUG=false
```

You don't want to leak information about your app's architecture or configuration. Usually, debug messages contain a lot of this kind of details.

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
    throw new \Exception('Whoops, something bad happended!');
}
```

## Restrict parts of your app with policies

[Policies](https://laravel.com/docs/authorization#creating-policies) in Laravel are like nightclub bouncers preventing people from accessing restricted areas.

Let's look at a real-world example of using a Policy:

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

As you can see, they make it really easy to check for permission before allowing a user to do anything.

Make sure to check the documentation, because there's a lot to learn about Policies.

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

Learn more about [validation in Laravel](https://laravel.com/docs/validation#main-content) on the official documentation.

## Be careful with uploaded files

As we saw, the user's input must never be trusted. That also goes for the files they upload. Here are a few recommendations:

1. Check the file's MIME type (Laravel has the right [validation rules](https://laravel.com/docs/10.x/validation#available-validation-rules) for that).

```php
$request->validate([
    'file' => 'required|mimes:gif,jpeg,png,webp',
]);
```

2. When possible, don't make uploaded files publicly accessible (using the `local` file driver, for instance).

3. Upload files on another server. If a hacker bypasses your securities, they won't be able to run unauthorized code and access sensitive information.

4. Delegate file uploads to a third-party service reputed for its security (meaning they never leaked data).

## Encrypt the payload of your jobs

Whenever you dispatch a job, its payload is saved into the database, Redis or whatever else you told Laravel to use using the `QUEUE_DRIVER` environment variable.

The payload may contain sensitive information that any of your employee may look at and potentially misuse. As I said in the beginning of this article, leaks are often initiated by employees.

Fortunately, Laravel provides a the `Illuminate\Contracts\Queue\ShouldBeEncrypted` Contract, which will automatically encrypt the payloads. To make sure nobody can unencrypt them, make sure the Laravel's `APP_KEY` defined in your *.env* is unaccessible to anyone besides a few trusted people.

```php
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;

class SomeJob implements ShouldQueue, ShouldBeEncrypted
{
    //
}
```

## Write tests for security risks

[Testing](https://laravel.com/docs/testing) is unfortunately a vast and lesser-known topic among developers.

Automatically testing multiple scenarii for potential security breaches is a great way to make sure they stay closed.

[Laracasts](https://laracasts.com) provides free testing courses to help you get started. One with PHPUnit, the industry standard, and one with [Pest](https://pestphp.com), the best testing framework on this planet that modernizes and simplifies testing in PHP.

- [PHP Testing Jargon](https://laracasts.com/topics/phpunit)
- [Pest From Scratch](https://laracasts.com/topics/pest)

![Keep your project tested](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/169/conversions/CleanShot_2023-07-31_at_20.03.50_2x_hfqpyx-medium.jpg)

## Do regular security audits

This practice is one of the most efficient and should be mandatory for anyone that is really serious about security. External feedback can be eyes opening.

As you can imagine, doing security audits isn't free. It might only be worth it for enterprise since it would cost even more to pay for the potential fines! You cannot put a price on maintaining a good reputation and the trust of your users.

---

Want more? Check out my other article ["Laravel best practices, tips and tricks"](https://benjamincrozat.com/laravel-best-practices).
