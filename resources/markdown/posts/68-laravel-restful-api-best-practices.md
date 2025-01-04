---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/1/lnPbc0JnlDlETESSHaqa5VeAxo9ZGg-metaYXBpX2R3ZjloYy5qcGc%3D-.jpg
Title: "7 Laravel RESTful APIs best practices for 2024"
Description: "Master the art of crafting RESTful APIs with Laravel thanks to these best practices."
Published at: 2023-09-06
Modified at: 2024-03-15
Categories: laravel
---

## Introduction to making REST APIs in Laravel

Crafting good APIs is a fundamental skill for any successful backend developer. **They're the pillars under your favorite mobile apps or single-page applications.**

Laravel provides a lot of tools to help you craft yours in a standard way. For this article, I will assume you've already built at least one, because my goal isn't to cover the subject in its entirety.

But if that's what you are looking for, you may be interested in Ash Allen's book on the matter titled "[Consuming APIs with Laravel](/recommends/consuming-apis-laravel)." Learning how to use them is the best way to become proficient in making REST APIs. More on that later!

## Laravel RESTful APIs best practices

### Use the right HTTP method

HTTP methods are kind of like the language that your web server speaks. When you're building an API, you need to speak it too.

Using the right HTTP methods is really important because it lets your API communicate more efficiently, and it will be more intuitive to the developers consuming it.

For example, let’s say we have a digital book in a library. If we want to read it, we'd use the `GET` method. If we want to add a new book into the library, we'd use the `POST` method. To update the information of an existing book, we would use the `PUT` or `PATCH` method (honestly, I still don't understand the difference, and I default to `PUT`). And finally, when we want to remove the book from the library, we use the `DELETE` method.

Now, how does Laravel help with this? It provides easy to use routes that match with these HTTP methods:

```php
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post}', [PostController::class, 'show']);
Route::post('/posts', [PostController::class, 'store']);
Route::put('/posts/{post}', [PostController::class, 'update']);
Route::delete('/posts/{post}', [PostController::class, 'destroy']);
```

If you don't know much about HTTP, I'd suggest you do a quick Google search to get familiar with it. I still haven't written about them (yet).

### Use API resources routes

Laravel provides an easy and convenient way to create API routes using [the `apiResource` method](https://laravel.com/docs/controllers#api-resource-routes). This helps developers quickly set up routes for their APIs.

Here is how it works:

In Laravel, `apiResource` is a method that automatically includes the basic routes we need for an API. It creates routes for *index*, *store*, *show*, *update*, and *destroy* methods, excluding *create* and *edit*, because those two are typically used to return HTML views which we don't need in an API.

For example:

```php
use App\Http\Controllers\PhotoController;

Route::apiResource('photos', PhotoController::class);
```

In the above code `photos` is the URL, and `PhotoController` is the class that handles the requests for this URL.

If you want to create routes for many controllers at once, simply use the `apiResources` method and pass an array having `'url' => 'Controller'` pairs like this:

```php
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PostController;
 
Route::apiResources([
    'photos' => PhotoController::class,
    'posts' => PostController::class,
]);
```

This automatically sets the URLs `photos` and `posts` to be handled by `PhotoController` and `PostController`, respectively.

Lastly, when creating a new Controller for your API, Laravel provides a handy command `php artisan make:controller ControllerName --api.` Adding the `--api` option will inform Laravel that this controller is for an API and will exclude the *create* and *edit* methods in the boilerplate code.

Laravel really makes setting up APIs a breeze!

### Use Eloquent's API resources

API resources are a way to turn data models into usable JSON structures. They provide a transformation layer between your models and the responses of your application's API.

Think of them as a middleman. They take data from your models, shuffle it around or filter it, and then hand it off as a JSON response.

When you generate a resource class, you can define how to map attributes from a model to the JSON representation. You simply use the `toArray` method to return an array that matches the structure you want in the JSON response. And you can access the model's properties right from your resource object.

Here's an example:

```php
public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'email' => $this->email,
    ];
}
```

Creating resources is done using the `make:resource` Artisan command. For example, if you wanted to create a UserResource, you would run:

```bash
php artisan make:resource UserResource
```

You can also create resource collections using `make:resource` with a `--collection` flag or by adding `Collection` to the resource name.

Here's an example of a UserResource "collection":

```php
php artisan make:resource User --collection
```

Or,

```php
php artisan make:resource UserCollection
```

In Laravel, you use these resources when crafting responses from route or controller.

For a single resource, you’d just return a new instance of the resource, made with the model you want to transform:

```php
Route::get('/user/{id}', function (string $id) {
    return new UserResource(User::findOrFail($id));
});
```

For collections, you’ll use the `collection` method on the resource class:

```php
Route::get('/users', function () {
    return UserResource::collection(User::all());
});
```

I recommend you to review Laravel's [full documentation on API resources](https://laravel.com/docs/eloquent-resources) if you want to learn about advanced functionalities and other usage scenarios.

### Use JSON responses

When you return an Eloquent resource in your controller, Laravel automatically sets up a JSON response.

In case you are not, though, the `json` method comes in handy to automatically set the `Content-Type` header to `application/json` and convert the given array to JSON:

```php
return response()->json([
    'foo' => 'bar',
]);
```

### Use the correct HTTP code for responses

Returning the right HTTP code in your response is crucial.

In your career, you probably stumbled upon terrible APIs returning a 200 status code with `{ "message": "Error!" }` or something like that. Please, don't be that developer!

We want our APIs to be as informative as possible. Here's a good starting point that will fit almost every use case:

- **Listing and getting resources**: 200 (OK).
- **Creating resources**: 201 (Created).
- **Updating resources**: 200 (OK).
- **Deleting resources**: 204 (No Content).
- **Need to be authenticated to access resources**: 401 (Unauthorized).
- **Unauthorized access to resources**: 403 (Forbidden).
- **Missing resources**: 404 (Not Found).
- **Something went wrong**: 500 (Internal Server Error).

Laravel provides a handy helper, `response()`, which lets us specify the HTTP code we need to include in our response:

```php
return response(
    ['foo' => 'bar'],
    201
);
```

Return an empty response with the 204 status code:

```php
response()->noContent();
```

A resource needs authentication, is unauthorized, missing, or something went wrong:

```php
abort(401);
abort(403);
abort(404);
abort(500);
```

By the way, if you have a valid use case for the [418 (I'm a teapot) status code](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/418), let me know!

### Save time on authentication using Laravel Sanctum or Passport

The nuance between those two packages is always tricky to explain. But let's give it a shot:

[Laravel Passport](https://laravel.com/docs/passport) can be used when you need authentication in the same fashion as Facebook, Google, X (formerly Twitter), etc. to authenticate your users.

[Laravel Sanctum](https://laravel.com/docs/sanctum), on the other hand, is like a less strict authentication system which works best for simple projects like single-page or mobile apps.

**Basically, if you are still unsure, rather than being stuck, use Laravel Sanctum first and upgrade to Passport if your app needs something more advanced. You will know it when the time comes!**

### Make sure the paths of your endpoints don't change

Here's a tip for people familiar with testing: **don't use the `route()` helper in your tests**. This is a tip coming from [@ModestasMV](https://twitter.com/ModestasMV) on X.

Let's make up a fictional and basic test:

```php
test('the endpoint works as expected', function () {
    $this
        ->get(route('foo'))
        ->assertOk();
});
```

Now, what if we change the path from */foo* to */bar*? **The test still passses, which is BAD.** A lot of users' code will break because of this.

So yeah, instead, do this:

```php
test('the endpoint works as expected', function () {
    $this
        ->get('/foo')
        ->assertOk();
});
```

Now, if for some reason your route's path changes to something else, the test will break and you won't be able to deploy in production.

## But wait, there's even more cool tips

If you enjoyed my article, you might be interested in checking out this incredible book I recently came across called "[Consuming APIs with Laravel](/recommends/consuming-apis-laravel)" by Ash Allen.

I haven't finished it yet, but it's been an absolute game-changer already. The book covers everything from different API types and authentication methods to using the powerful [Saloon](https://docs.saloon.dev) library and handling webhooks like a 10x developer.

What I really love is how Ash dives deep into testing strategies and best practices, ensuring that your API integrations are not only feature-rich but also reliable and secure. So yeah, I highly recommend giving this book a read. It's definitely worth the investment!

[Check out Consuming APIs with Laravel by Ash Allen](/recommends/consuming-apis-laravel)

[![The Consuming APIs with Laravel book by Ash Allen.](https://github.com/benjamincrozat/content/assets/3613731/5f5e61b5-1ce1-4a31-9ca3-c7ef0cf59636)](/recommends/consuming-apis-laravel)
