---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/57/4200_3_02_bd0mps.jpg
Title: "6 pull requests merged in Laravel during week 34 of 2023"
Description: "Exciting pull requests merged in Laravel, including improved testing, transaction fixes, and memory-efficient failed job providers!"
Published at: 2023-09-01
Categories: laravel
---

## Introduction

Buckle up, esteemed colleagues! Some of this week's merged pull requests in Laravel give us a mix of improved testing capabilities, seamless handling of job lifecycle hooks, updates for query builders and relations, and additional support for failed job providers! 

## What was merged in Laravel

### A more reliable way to test jobs with BusFake and QueueFake

In the first highlight of the week, [Luke Kuzmish](https://github.com/cosmastech) brings us a wonderful addition to enhance our testing capabilities. The `serializeAndRestoreJobs()` method simulates the queuing process and detects potential serialization errors during tests rather than waiting for unpleasant surprises in production. This baby makes test suite failures easier to anticipate and debug! Amazing, right? Kudos to Luke!

Learn more from [pull request #1483009836](https://github.com/laravel/framework/pull/48131).

```php
public function testCanDispatchJobImproved()
{
    Queue::fake()->serializeAndRestore();

    MyJob::dispatch(fn() => true); // ❌ Exception  Serialization of 'Closure' is not allowed. 🥳 
}
```

### createOrFirst() now plays nice with transactions

Next, we see an astute fix by [Tony Messias](https://github.com/tonysm) on `createOrFirst` methods in transactions. Using the new `withSavepointIfNeeded(fn)` method, Tony ensures that the `create` or `attach` parts get wrapped within a save point transaction, alleviating issues that might occur without it. A big thumbs-up to Tony!

Catch all the sparkles in [pull request #1485758586](https://github.com/laravel/framework/pull/48144).

### Countable failed job providers

[Tim MacDonald](https://github.com/timacdonald) swoops in with a solution for more efficient handling of failed jobs. By making the 1st party drivers `Countable` without introducing a new method on the interface, Tim saves us from retrieve all records into memory and perform an in-memory count. A life-saver for applications with a large volume of failed jobs! Hats off to Tim!

Check out all details in [pull request #1489028327](https://github.com/laravel/framework/pull/48177).

### Job UUID in the JobQueued event

Tim is back in action with an addition that makes tracking a job from being queued to all its lifecycle hooks easier. By providing access to the job's UUID, which doesn't change when a job fails and is retried, we get a robust solution for handling job lifecycle hooks. Top-notch work, Tim!

Get more insights from [pull request #1489134277](https://github.com/laravel/framework/pull/48179).

### Artisan becomes more flexible

[Nuno Maduro](https://github.com/nunomaduro) spices things up with his work on slimming Laravel's 11 skeleton. He allows for chaining a `schedule` methods directly on the `Artisan::command` call, which makes the syntax more concise and easier to read. Keep it up, Nuno!

Flex into [pull request #1484641172](https://github.com/laravel/framework/pull/48137).

### A new and more relaxed JSON assertion

In his PR, [Günther Debrauwer](https://github.com/gdebrauwer) brilliantly circumvents a common issue when testing API calls by introducing the new method `assertJsonPathCanonicalizing`. Once more, testing within Laravel becomes a smoother process thanks to Günther's contribution!

Deep dive into [pull request #1480973461](https://github.com/laravel/framework/pull/48117).

```php
$users = User::factory()->count(2)->create();

$this->get('/api/users')->assertJsonPathCanonicalizing('data.*.id', $users->pluck('id')->all());
```

## Conclusion

That’s all for this week, folks! I hope you found these updates enlightening.

Are you ready to dive into the next week's Laravel adventures? I'll bring you more stories of code bravado and technical brilliance. Remember to share these updates with your teammates and other developers who work with Laravel. Happy coding!
