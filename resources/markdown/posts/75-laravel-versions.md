---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/178/sngqsGxoLry9qHLY5j7XaXm5dLJ5C5-metabGFyYXZlbC5qcGc%3D-.jpg
Title: "A complete history of Laravel's versions (2011-2024)"
Description: "What's the current version of Laravel? Is there any LTS release you can rely on? And what about the history of the framework? Let's find out!"
Published at: 2023-09-20
Modified at: 2024-02-10
Categories: laravel
---

## Introduction

To me, Laravel is one of the best things that happened to PHP. I started using it in 2015 when version 5.1 was released, and I wish I started earlier.

It's fascinating to go back in time and have an overview of how far the framework has come.

## The latest stable version of Laravel

**The latest stable release of Laravel is version 11.** It's been publicly available since March 12, 2024. This version introduced numerous [new features and changes](/laravel-11). Laravel 11 will be supported until September 3rd, 2025 for bug fixes and until March 12th, 2026 for security fixes.

## The latest LTS (Long Term Support) release of Laravel

Nope, Laravel 11 isn't LTS. **The latest LTS release of Laravel was version 6.** It was supported from September 3rd, 2019 to September 3rd, 2022 for new releases, bug fixes, and security fixes.

Starting with Laravel 8.0, LTS versions became absolete as every new major version is supported for two years, giving everyone enough time to upgrade to next big release.

Trust my experience; if you need an LTS version of Laravel, this means something is wrong with your codebase and you should take action to fix this.

For posterity, though, here's a list of every LTS version of Laravel:
- Laravel 5.1
- Laravel 5.5
- Laravel 6

## The currently supported Laravel versions

The currently supported Laravel versions are:

- **Laravel 11**:
    - Laravel 11 is still the current version of the framework and receives weekly updates for new features, bug fixes, and security fixes.
- **Laravel 10**:
    - Laravel 10 is still supported and will receive bug fixes until August 7, 2024, and security fixes until February 7, 2025.

## See which version of Laravel you are running

Knowing which version of Laravel you are running is a crucial piece of information, whether you’re planning to upgrade, debug, or simply want to ensure compatibility with a specific feature.

Check out: [Ways to check which Laravel version you are running](https://benjamincrozat.com/check-laravel-version)

## Laravel versions history from 2011 to 2023

### Laravel version 1.0

If you ever asked yourself when was Laravel version 1.0 came to existence, it was **in June 2011**.

According to [Wikipedia](https://en.wikipedia.org/wiki/Laravel), Laravel 1 included built-in support for authentication, localisation, models, views, sessions, routing and other mechanisms. But it lacked support for controllers, which prevented it from being a true MVC framework.

![Laravel's official website in 2011.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/180/e64VXzjPj90dIkxikqTk35wXFTSw7Q-metaQ2xlYW5TaG90IDIwMjMtMDktMjAgYXQgMTMuMTUuMDdAMnguanBn-.jpg)

### Laravel version 2.0

**Laravel version 2.0 was released shortly after version 1 in September 2011.**

Some of its major new features included the support for controllers, which finally made Laravel 2 a fully MVC-compliant framework. It also included built-in support for the inversion of control (IoC) principle, and a templating system called Blade.

As a downside, support for third-party packages was removed in Laravel 2. I wasn't using Laravel at this time, but if I had to guess why support for third-party packages was removed, it's probably because the framework wasn't using Composer yet (the package manager was out for just a few months at this moment). But take it with a grain of salt.

### Laravel version 3.0

**Laravel version 3.0 was released in February 22, 2012.**

Notably, this version brought Artisan to the forefront. Additionally, it expanded its compatibility with various database systems, introduced database migrations for better layout version control, offered event handling capabilities, and unveiled a new packaging system named Bundles. This release significantly boosted Laravel's popularity.

You can learn more in this great blog post I found: [History of Laravel PHP framework, Eloquence emerging](https://maxoffsky.com/code-blog/history-of-laravel-php-framework-eloquence-emerging/).

![Laravel's official website in September 2012](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/182/2m9KNjCRMxrSW9PUDdbLRMaWvH5Ztd-metaQ2xlYW5TaG90IDIwMjMtMDktMjAgYXQgMTMuMjAuNDhAMnguanBlZw%3D%3D-.jpg)

### Laravel version 3.1

**Laravel 3.1 was released in March 27, 2012.** I will compile a list of the features that were included with it whenever I find a reliable source.

### Laravel version 3.2

**Laravel 3 was released in May 22, 2012.** I will compile a list of the features that were included with it whenever I find a reliable source.

### Laravel version 4.0

**Laravel 4.0, codenamed Illuminate, was released in May 28, 2013.** Some of its new features include:

- **Rewrite of the codebase**:
    - The framework is now based on multiple packages.
    - These packages are distributed through Composer.

- **Built-in support for message queues.**

- **Built-in support for sending different types of email.**

- **Support for delayed deletion of database records called soft deletion.**

### Laravel version 4.1

**Laravel 4.1 version, codenamed Illuminate, was released in December 12, 2013.** Some of its new features include:

- **New SSH component**:
    - Allows you to SSH into remote servers and run commands.
    - Introduced `php artisan tail` command using the new SSH component.

- **Boris in Tinker**:
    - `php artisan tinker` now utilizes the Boris REPL if system supports.
    - Requires readline and pcntl PHP extensions.
    - If extensions aren't installed, shell from 4.0 will be used.

- **Eloquent improvements**:
    - Introduced `hasManyThrough` relationship.
    - Introduced `whereHas` method to retrieve models based on relationship constraints.

- **Database read/write connections**:
    - Supports automatic handling of separate read/write connections in query builder and Eloquent.

- **Queue priority**:
    - Queue priorities can be set by passing a comma-delimited list to `queue:listen` command.

- **Failed queue job handling**:
    - Automatic handling of failed jobs using the `--tries` switch on `queue:listen`.

- **Cache tags**:
    - Replaces cache "sections" with "tags".
    - Assign multiple "tags" to a cache item.
    - Flush all items assigned to a single tag.

- **Flexible password reminders**:
    - Enhanced engine provides more flexibility for password validation, flashing messages, etc.

- **Improved routing engine**:
    - Rewritten routing layer, maintaining same API.
    - Route registration 100% faster than in 4.0.
    - Reduced dependency on Symfony Routing.

- **Improved session engine**:
    - New session engine introduced.
    - No longer uses Symfony's and PHP's session handling. Uses custom solution instead.

- **Doctrine DBAL**:
    - For `renameColumn` in migrations, `doctrine/dbal` dependency should be added to `composer.json`.
    - Package is no longer included in Laravel by default.

### Laravel version 4.2

![Laravel's official website in October 2014.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/185/Hd8LnR3jnu6IZsr7Hj4eEPaHefA2Mw-metaQ2xlYW5TaG90IDIwMjMtMDktMjAgYXQgMTMuMzEuNTNAMnguanBn-.jpg)

**Laravel version 4.2, codenamed Illuminate, was released in June 1, 2014.** Some of its new features include:

- **Daemon queue workers**:
    - Artisan queue:work command supports a --daemon option.
    - Worker processes jobs without re-booting the framework.
    - Reduction in CPU usage but slightly more complex deployment process.

- **Mail API drivers**:
    - Introduces new Mailgun and Mandrill API drivers.
    - Provides faster and more reliable method of sending emails than SMTP options.
    - Utilizes the Guzzle 4 HTTP library.

- **Soft deleting traits**:
    - Cleaner architecture for "soft deletes" and "global scopes" introduced via PHP 5.4 traits.
    - Easier construction of global traits and cleaner separation within the framework.

- **Convenient auth & remindable traits**:
    - Uses traits for authentication and password reminder user interfaces.
    - Provides a cleaner default User model file.

- **"Simple paginate"**:
    - New simplePaginate method added to query and Eloquent builder.
    - More efficient queries for simple pagination.

- **Migration confirmation**:
    - Destructive migration operations ask for confirmation in production.
    - Commands can be forced to run without prompts using the --force command.

### Laravel version 5.0

![Laravel's official website in February 2015](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/181/kDqrJE6HsusDCXyIozuq6dB8GkLyDp-metaQ2xlYW5TaG90IDIwMjMtMDktMjAgYXQgMTMuMjMuMDhAMnguanBlZw%3D%3D-.jpg)

**Laravel version 5.0 was released in February 2015.** Some of its new features include:

- **New Folder Structure**:
    - Removed app/models directory.
    - Code moved to the app folder, organized under the App namespace.
    - Controllers, middleware, and requests now under app/Http directory.
    - Middleware broken into their own class files.
    - Introduced app/Providers directory replacing app/start files.
    - Moved language files and views to the resources directory.

- **Contracts**:
    - All major components implement interfaces located in the illuminate/contracts repository.

- **Route Cache**:
    - New route:cache Artisan command for faster route registration.

- **Route Middleware**:
    - Supports HTTP middleware.
    - Authentication and CSRF "filters" converted to middleware.

- **Controller Method Injection**:
    - Supports type-hint dependencies on controller methods.

- **Authentication Scaffolding**:
    - Includes user registration, authentication, and password reset controllers.
    - Provided simple views located at resources/views/auth.
    - Included "users" table migration.

- **Event Objects**:
    - Define events as objects.
    - Dispatch events using Event::fire().

- **Commands / Queueing**:
    - Represent queued jobs as command objects in app/Commands directory.
    - Supports synchronous task execution using commands.

- **Database Queue**:
    - Introduced a database queue driver.

- **Laravel Scheduler**:
    - Fluent and expressive definition of command schedules.
    - Requires only a single Cron entry on the server.

- **Tinker / Psysh**:
    - php artisan tinker command now uses Psysh.

- **DotEnv**:
    - Utilizes DotEnv for environment configuration management.

- **Laravel Elixir**:
    - Provides interface to compile and concatenate assets using Gulp.

- **Laravel Socialite**:
    - Offers authentication with OAuth providers including Facebook, Twitter, Google, and GitHub.

- **Flysystem Integration**:
    - Integration with Flysystem filesystem abstraction library.
    - Supports local, Amazon S3, and Rackspace cloud storage.

- **Form Requests**:
    - Introduced form requests extending the Illuminate\Foundation\Http\FormRequest class.
    - Automatically validates request inputs.

- **Simple Controller Request Validation**:
    - Base controller includes a ValidatesRequests trait for simpler validation.

- **New Generators**:
    - Added new Artisan generator commands.

- **Configuration Cache**:
    - Cache all configuration using the config:cache command.

- **Symfony VarDumper**:
    - Upgraded dd helper function to use Symfony VarDumper for enhanced debug information.

### Laravel version 5.1 LTS

**Laravel version 5.1, released in June 2015.** This is when I started using the framework! Some of its new features include:

- **Event Broadcasting**:
    - Facilitates broadcasting of events over a websocket connection.
    - Helps in building realtime, live-updating user interfaces.

- **Middleware Parameters**:
    - Middleware can now receive additional custom parameters.
    - Example included of a RoleMiddleware that checks for a user role.

- **Testing Overhaul**:
    - Improved built-in testing capabilities.
    - Introduced new methods for fluent and expressive interaction with the application during testing.

- **Model Factories**:
    - Allows creation of stub Eloquent models using model factories.
    - Makes use of the Faker PHP library for generating random attribute data.

- **Artisan Improvements**:
    - Commands can be defined using a simple, route-like "signature".
    - Simplified interface for defining command line arguments and options.

- **Folder Structure**:
    - Renamed app/Commands directory to app/Jobs.
    - Consolidated app/Handlers into a single app/Listeners directory.

- **Encryption**:
    - Moved from mcrypt PHP extension to openssl extension for handling encryption.


### Laravel version 5.2

**Laravel version 5.2 was released in December 2015.** Some of its new features include:

- **Authentication drivers / "Multi-auth"**:
    - Added support for multiple authentication drivers.
    - Ability to define multiple authenticatable models or user tables.
    - Control authentication processes separately for different user tables.

- **Authentication scaffolding**:
    - Added command for scaffolding authentication views: `php artisan make:auth`.
    - Generates Bootstrap-compatible views for user login, registration, and password reset.
    - Automatically updates the routes file.

- **Implicit model binding**:
    - Inject relevant models directly into routes and controllers.
    - No need for `Route::model` method to inject a model.
    - Model injection based on the URI segment and type-hinting.

- **Middleware groups**:
    - Group multiple route middleware under a single key.
    - Comes with default groups like "web" and "api".
    - "web" group includes session and CSRF-related middlewares.
    - "api" group can include a rate limiter.

- **Rate limiting**:
    - New rate limiter middleware.
    - Limit number of requests based on IP over a time span.

- **Array validation**:
    - Improved validation for array form input fields.
    - Ability to validate each element in an array using the * character.
    - Simplified validation messages for array-based fields.

- **Bail validation rule**:
    - New validation rule to stop validating after first validation failure.
    - Useful to prevent further checks if a preceding check fails.

- **Eloquent global scope improvements**:
    - Easier and less error-prone global Eloquent scopes.
    - Implement a single `apply` method for global scopes.


### Laravel version 5.3

**Laravel version 5.3 was released on August 23, 2016.** Some of its new features include:

- **Notifications**:
    - Driver based notification system.
    - Deliver notifications across multiple channels like email, Slack, and SMS.
    - Use a simple method to notify: `$user->notify(new InvoicePaid($invoice));`
    - Community written drivers for notifications, including iOS and Android.

- **WebSockets / Event broadcasting**:
    - Channel-level authentication for private and presence WebSocket channels.
    - Introduction of Laravel Echo for subscribing to channels and listening to server-side events.
    - Support for both Pusher and Socket.io with Laravel Echo.
    - Presence channels that provide information about who is listening.

- **Laravel Passport (OAuth2 server)**:
    - Full OAuth2 server implementation.
    - Issue access tokens via OAuth2 authorization codes.
    - Allow users to create personal access tokens.
    - Vue components included for OAuth2 dashboard.
    - Define access token scopes.
    - Middleware for verifying access token scopes.
    - Support for consuming your own API without managing access tokens.

- **Search (Laravel Scout)**:
    - Driver based solution for adding full-text search to Eloquent models.
    - Auto-sync search indexes with Eloquent records.
    - Algolia driver included by default.
    - Make models searchable using the Searchable trait.

- **Mailable objects**:
    - Represent email messages as objects.
    - Send emails using a simple API.
    - Mailable objects can be marked as "queueable".

- **Storing uploaded files**:
    - Store user uploaded files easily.
    - New store method on an uploaded file instance.

- **Webpack & Laravel Elixir**:
    - Laravel Elixir 6.0 with support for Webpack and Rollup JavaScript module bundlers.
    - Default `gulpfile.js` uses Webpack for JavaScript compilation.

- **Frontend structure**:
    - Modern frontend structure introduced.
    - Changes in `make:auth` authentication scaffolding.
    - Support for single file Vue components.
    - Guidance on modern, robust JavaScript application development.

- **Routes files**:
    - Two HTTP route files in a top-level routes directory.
    - Separate files for web and API routes.

- **Closure console commands**:
    - Define Artisan commands as Closures.
    - New `routes/console.php` file for Closure based Console commands.

- **The $loop variable**:
    - `$loop` variable available in Blade loops.
    - Access loop index, check if it's the first or last iteration.


### Laravel version 5.4

**Laravel version 5.4 was released on January 24, 2017.** Some of its new features include:

- **Markdown mail & notifications**:
    - Support for Markdown-based emails and notifications.
    - Pre-built templates and components of mail notifications in mailables.
    - Render responsive HTML templates and generate a plain-text counterpart.
    - Use `vendor:publish` to export Markdown mail components.

- **Laravel Dusk**:
    - Expressive, easy-to-use browser automation and testing API.
    - Uses standalone ChromeDriver by default, but supports other Selenium-compatible drivers.
    - Enables testing and interaction with JavaScript heavy applications.

- **Laravel Mix**:
    - Spiritual successor of Laravel Elixir.
    - Based on Webpack instead of Gulp.
    - Fluent API for defining Webpack build steps using CSS and JavaScript pre-processors.

- **Blade components & slots**:
    - Introduced components and slots for blade templates.
    - Provides benefits similar to sections and layouts.
    - Support for named slots.

- **Broadcast model binding**:
    - Support for implicit and explicit route model binding for channel routes.
    - Can receive actual model instances instead of just string or numeric IDs.

- **Collection higher order messages**:
    - Shortcuts for common actions on collections.
    - Accessible as dynamic properties on collection instances.

- **Object based Eloquent events**:
    - Eloquent event handlers can be mapped to event objects.
    - Provides an intuitive way of handling Eloquent events.

- **Job level retry & timeout**:
    - Job "retry" and "timeout" settings configurable on a per-job basis.
    - Defined directly on the job class.

- **Request sanitization middleware**:
    - Introduced `TrimStrings` and `ConvertEmptyStringsToNull` middleware.
    - Automatically trims request input values and converts empty strings to null.

- **"Realtime" facades**:
    - Convert any class into a facade in realtime.
    - Prefix the imported class name with `Facades`.

- **Custom pivot table models**:
    - Define custom models for pivot tables in belongsToMany relationships.

- **Improved Redis cluster support**:
    - Define Redis connections to multiple single hosts and multiple clusters in the same application.

- **Migration default string length**:
    - Uses utf8mb4 character set by default.
    - Support for storing "emojis" in the database.
    - For older MySQL versions, configure default string length using `Schema::defaultStringLength`.

### Laravel version 5.5 LTS

![Laravel's official website in August 2017](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/183/conversions/92WuvFkMw4kpci3ZZhUZNC72W9CBRy-metaQ2xlYW5TaG90IDIwMjMtMDktMjAgYXQgMTMuMjUuNDlAMnguanBlZw%3D%3D--medium.jpg)

**Laravel version 5.5 LTS was released on August 30, 2017.** Some of its new features include:

- **On-demand notifications**:
    - Route ad-hoc notification routing using `Notification::route`.
    - Send notifications to someone not stored as a user.

- **Renderable mailables**:
    - Mailables can be returned directly from routes.
    - Quick preview of mailable designs in the browser.

- **Renderable & reportable exceptions**:
    - Define a render method directly on exceptions.
    - Define a report method on exceptions to customize reporting logic.
    - Avoid conditional logic accumulation in your exception handler.

- **Request balidation**:
    - Use the `validate` method on `Illuminate\Http\Request` object.
    - Quickly validate incoming requests from route closures or controllers.

- **Consistent exception handling**:
    - Consistent handling of validation exceptions throughout the framework.
    - Control all JSON validation error formatting by defining a single method on `App\Exceptions\Handler` class.

- **Cache locks**:
    - Redis and Memcached cache drivers support for obtaining and releasing atomic "locks".
    - Obtain locks without worrying about race conditions.
    - Block until the lock becomes available.

- **Blade improvements**:
    - Define custom conditional directives using `Blade::if`.
    - New shortcuts for checking authentication status (`@auth`, `@guest`).

- **New routing methods**:
    - Use `Route::redirect` method for routes that redirect to another URI.
    - Use `Route::view` method for routes that only need to return a view.

- **Sticky database connections**:
    - New sticky configuration option available in read/write database connections.
    - Allows immediate reading of records written during the current request cycle.

### Laravel version 5.6

**Laravel version 5.6 was released on February 7, 2018.** Some of its new features include:

- **Logging improvements**:
    - New `config/logging.php` configuration file.
    - Ability to build logging "stacks" for multiple handlers.
    - New "tap" functionality for customizing log channels.

- **Single server task scheduling**:
    - Limitation of scheduled job to execute on a single server.
    - Use of the `onOneServer` method to indicate task should run on only one server.
    - Requirement of memcached or redis cache driver as default cache driver.

- **Dynamic rate limiting**:
    - Ability to specify dynamic request maximum based on authenticated User model attribute.
    - Use of "throttle:rate_limit,1" middleware to achieve dynamic rate limiting.

- **Broadcast channel classes**:
    - Use of channel classes instead of Closures for authorization.
    - Generation of channel classes using `make:channel` Artisan command.
    - Authorization logic placed in the channel class' `join` method.

- **API controller generation**:
    - `--api` switch for the `make:controller` command to generate resource controller without methods presenting HTML templates.

- **Model serialization improvements**:
    - Queued models with their loaded relationships intact are automatically re-loaded when job is processed.

- **Eloquent date casting**:
    - Ability to customize the format of Eloquent date cast columns.
    - Specification of desired date format within the cast declaration.

- **Blade component aliases**:
    - Aliasing of Blade components stored in a sub-directory.
    - Rendering aliased components using a directive.

- **Argon2 password hashing**:
    - Support for password hashing via Argon2 algorithm for PHP 7.2.0 or greater.
    - New `config/hashing.php` configuration file.

- **UUID methods**:
    - Introduction of `Str::uuid` and `Str::orderedUuid` for generating UUIDs.
    - Ordered UUIDs for better indexing by databases.

- **Collision**:
    - Inclusion of dev Composer dependency for Collision package.
    - Enhanced error reporting when interacting with Laravel on the command line.

- **Bootstrap 4**:
    - Upgrade of all front-end scaffolding to Bootstrap 4.
    - Default pagination link generation now uses Bootstrap 4.


### Laravel version 5.7

**Laravel version 5.7 was released on September 4, 2018.** Some of its new features include:

- **Laravel Nova**:
    - Administer underlying database records using Eloquent.
    - Supports filters, lenses, actions, queued actions, metrics, authorization, custom tools, custom cards, and custom fields.

- **Email Verification**:
    - Optional email verification added to authentication scaffolding.
    - Added `email_verified_at` timestamp column to default users table migration.
    - User model can be marked with the `MustVerifyEmail` interface to prompt email verification for newly registered users.
    - Introduced a `verified` middleware for routes that should only allow verified users.

- **Guest User Gates / Policies**:
    - Authorization gates and policies can now allow guests through by declaring an "optional" type-hint or supplying a null default value for the user argument.

- **Symfony Dump Server**:
    - Integration with Symfony's dump-server command.
    - Calls to dump will be displayed in dump-server console instead of the browser.

- **Notification Localization**:
    - Send notifications in a locale other than the current language.
    - Notifications remember the set locale even if they are queued.
    - Use `locale` method on `Illuminate\Notifications\Notification` class to set the desired language.

- **Console Testing**:
    - "Mock" user input for console commands using the `expectsQuestion` method.
    - Specify exit code and text expected to be output by the console command using the `assertExitCode` and `expectsOutput` methods.

- **URL Generator & Callable Syntax**:
    - URL generator now accepts "callable" syntax when generating URLs to controller actions.

- **Paginator Links**:
    - Control how many additional links are displayed on each side of the paginator's URL "window" using the `onEachSide` method.

- **Filesystem Read / Write Streams**:
    - Flysystem integration now provides `readStream` and `writeStream` methods for handling file streams.

### Laravel version 5.8

**Laravel 5.8 was released on February 26, 2019.** Some of its new features include:

- **Eloquent hasOneThrough relationship**.

- **Auto-discovery of model policies**:
    - Policies are auto-discovered if they follow Laravel naming conventions.
    - Policies in `app/Policies` directory with a matching `Policy` suffix are auto-discovered.
    - Custom policy discovery logic can be registered with `Gate::guessPolicyNamesUsing`.

- **PSR-16 cache compliance**:
    - Cache item time-to-live changed from minutes to seconds.
    - Methods such as `put`, `putMany`, `add`, and `remember` were updated to this behavior.

- **Multiple broadcast authentication guards**:
    - Ability to assign multiple authentication guards to broadcast channels.
    - Example given with `['guards' => ['web', 'admin']]`.

- **Token guard token hashing**:
    - Token guard supports storing API tokens as SHA-256 hashes.
    - Provides improved security over storing plain-text tokens.

- **Improved email validation**:
    - Adoption of the `egulias/email-validator` package.
    - Addresses issues with valid emails being considered invalid.

- **Default scheduler timezone**:
    - Ability to define a default timezone for all scheduled tasks.
    - A `scheduleTimezone` method can be added to `app/Console/Kernel.php`.

- **Intermediate table / pivot model events**:
    - Eloquent model events are dispatched for custom pivot models during many-to-many operations.

- **Artisan call improvements**:
    - Ability to pass command options as a single string.
    - Example provided with `Artisan::call('migrate:install --database=foo')`.

- **Mock / spy testing helper methods**:
    - Added `mock` and `spy` methods to base test case class.
    - Automates binding of the mocked class into the container.

- **Eloquent resource key preservation**:
    - Ability to preserve collection keys when returning an Eloquent resource collection.
    - `preserveKeys` property can be added to resource classes.

- **Higher order orWhere Eloquent method**:
    - Allows fluent chaining of scopes without use of Closures.
    - Example given with `User::popular()->orWhere->active()`.

- **Artisan serve improvements**:
    - Automatically scans for available ports up to 8009.
    - Allows multiple applications to be served simultaneously.

- **Blade file mapping**:
    - Compiled Blade templates now contain a comment with the path to the original template.

- **DynamoDB cache / session drivers**:
    - Introduced DynamoDB cache and session drivers.

- **Carbon 2.0 support**:
    - Laravel 5.8 supports Carbon ~2.0 release.

- **Pheanstalk 4.0 support**:
    - Laravel 5.8 supports Pheanstalk ~4.0 release.


### Laravel version 6.0 LTS

![Laravel's official website in September 2019.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/184/conversions/xbyiQDQD0yQT3CsedXiyfgVe3h71V5-metaQ2xlYW5TaG90IDIwMjMtMDktMjAgYXQgMTMuMjguMDBAMnguanBlZw%3D%3D--medium.jpg)

**Laravel version 6.0 LTS was released on September 3, 2019.** Some of its new features include:

- **Semantic versioning**:
    - The Laravel framework (laravel/framework) package now follows the semantic versioning standard.

- **Laravel Vapor compatibility**:
    - Provides compatibility with Laravel Vapor, an auto-scaling serverless deployment platform.
    - Vapor abstracts the complexity of managing Laravel applications on AWS Lambda and interfaces with SQS queues, databases, Redis clusters, networks, CloudFront CDN, and more.

- **Improved exceptions via Ignition**:
    - Ships with Ignition, a new open source exception detail page.
    - Ignition offers improved Blade error file and line number handling, runnable solutions for common problems, code editing, exception sharing, and an improved UX.

- **Improved authorization responses**:
    - Easier retrieval and exposure of custom authorization messages to end users.
    - Authorization policy's response and message can be retrieved using the Gate::inspect method.

- **Job middleware**:
    - Allows custom logic wrapping around the execution of queued jobs, reducing boilerplate in the jobs themselves.
    - Middleware can be created and attached to jobs, allowing for separation of concerns.

- **Lazy collections**:
    - Introduces a LazyCollection, leveraging PHP's generators to work with very large datasets with low memory usage.
    - The query builder's cursor method returns a LazyCollection instance, allowing efficient iteration over large datasets.

- **Eloquent subquery enhancements**:
    - New enhancements and improvements to database subquery support.
    - Allows selection of related data using subqueries in both selection and ordering of main query.

- **Laravel UI**:
    - Frontend scaffolding has been extracted into a laravel/ui Composer package.
    - No Bootstrap or Vue code in default framework scaffolding.
    - The make:auth command has been extracted from the framework.
    - Use the ui Artisan command to install frontend scaffolding after installing the laravel/ui package.

### Laravel version 7.0

**Laravel version 7.0 was released on March 3, 2020.** Some of its new features include:

- **Multiple mail driver support**:
    - Configuration of multiple "mailers" for a single application.
    - Each mailer may have its own options and unique "transport".
    - Allows the use of different email services to send specific types of emails.
    - Default mailer used can be overridden with the `mailer` method.

- **Route caching speed improvements**:
    - New method for matching compiled, cached routes.
    - Can lead to a 2x speed improvement on large applications.

- **CORS support**:
    - First-party support for configuring Cross-Origin Resource Sharing (CORS) OPTIONS request responses.
    - Integration of the popular Laravel CORS package.
    - New `cors` configuration included in the default Laravel application skeleton.

- **Query time casts**:
    - Ability to apply casts while executing a query.
    - Useful when selecting raw values from a table.
    - Uses the `withCasts` method.

- **MySQL 8+ database queue improvements**:
    - Enhancements for applications using MySQL 8+ for database-backed queue.
    - Utilizes the `FOR UPDATE SKIP LOCKED` clause and other SQL improvements.
    - Suitable for higher volume production applications.

- **Artisan test command**:
    - Additional command to run tests beyond the `phpunit` command.
    - Provides better console UX.
    - Stops automatically on the first test failure.

- **Markdown mail template improvements**:
    - New modern design based on the Tailwind CSS color palette.
    - Templates can be published and customized.

- **Stub customization**:
    - `make` commands in Artisan console use "stub" files for class generation.
    - Laravel 7 introduced the `stub:publish` command to customize common stubs.
    - Published stubs reside in a `stubs` directory in the root.

- **Queue maxExceptions configuration**:
    - Define how many exceptions a job can have before being considered failed.
    - Ability to set a `maxExceptions` property on job classes.
    - Jobs will continue to be retried until a set number of unhandled exceptions are reached.

### Laravel version 8.0

**Laravel version 8.0 was released on September 8, 2020.** Some of its new features include:

- **Event listener improvements**:
    - Closure based event listeners can now be registered by only passing the closure to the `Event::listen` method.
    - Closure based event listeners can be marked as queueable using the `Illuminate\Events\queueable` function.
    - Queued listeners can be customized using the `onConnection`, `onQueue`, and `delay` methods.
    - Handle anonymous queued listener failures using the `catch` method.

- **Time testing helpers**:
    - Helpers added to modify the time returned by functions such as `now` or `Illuminate\Support\Carbon::now`.
    - Travel into the future with methods like `travel()->seconds()`, `travel()->days()`, etc.
    - Travel into the past using negative values like `travel(-5)->hours()`.
    - Travel to an explicit time using `travelTo()` function.
    - Return back to the present time with the `travelBack()` function.

- **Artisan serve improvements**:
    - `artisan serve` now has automatic reloading when environment variable changes are detected in the `.env` file.

- **Laravel paginator update**:
    - Updated to use the Tailwind CSS framework by default.
    - Bootstrap 3 and 4 views remain available.

- **RouteServiceProvider changes**:
    - In Laravel 8.x, the `$namespace` property is null by default, meaning no automatic namespace prefixing.
    - Controller route definitions should now use standard PHP callable syntax.
    - Calls to action related methods should use the same callable syntax.
    - To use Laravel 7.x style controller route prefixing, add the `$namespace` property back into your `RouteServiceProvider`.

### Laravel version 9.0

**Laravel version 9.0 was released on February 15, 2022.** Some of its new features include:

- **Improved validation of nested array inputs**:
    - Access the value for a given nested array element when assigning validation rules.
    - Use the Rule::forEach method to assign validation to array elements.

- **Laravel Breeze API scaffolding and Next.js starter kit**:
    - Received an "API" scaffolding mode.
    - Complimentary Next.js frontend implementation.
    - Can be used to start Laravel applications serving as a backend API for a JS frontend.

- **Ignition redesign**:
    - Open source exception debug page redesigned.
    - Includes light/dark themes.
    - Customizable "open in editor" functionality.

- **Improved route:list CLI output**:
    - Significant improvement in output for Laravel 9.x.
    - Enhanced experience when exploring route definitions.

- **Test coverage using Artisan test command**:
    - New --coverage option to explore test coverage.
    - Display test coverage directly in the CLI output.
    - New --min option to specify a minimum test coverage percentage.

- **Soketi Echo server**:
    - Laravel Echo compatible Web Socket server for Node.js.
    - Open source alternative to Pusher and Ably.
    - Consult broadcasting documentation and Soketi documentation.

- **Improved collections IDE support**:
    - Added "generic" style type definitions for better IDE and static analysis support.
    - Improved understanding for tools like PHPStorm and PHPStan.

- **New helper functions**:
    - `str` function returns a new Stringable instance or the Str instance.
    - `to_route` function generates a redirect HTTP response for a named route.

### Laravel version 10.0

**Laravel version 10.0 was released on February 14, 2023.** Some of its new features include:

- **Argument and return types**:
    - Introduced to all application skeleton methods.
    - Applied to all stub files used to generate classes throughout the framework.
    - Extraneous "doc block" type-hint information has been deleted.
    - Entirely backwards compatible with existing applications.

- **Developer-friendly abstraction layer for external processes**:
    - New `Process` facade for starting and interacting with external processes.
    - Ability to start processes in pools for concurrent execution and management.
    - Fake processes for convenient testing.
    - Process assertions for testing like `Process::assertRan('ls -la')`.

- **Laravel Pennant**:
    - A new first-party package for managing application's feature flags.
    - In-memory array driver and a database driver for persistent feature storage included.
    - Easy definition of features using the `Feature::define` method.
    - Blade directives for convenience when checking if a feature is active.

- **Test profiling for Artisan test command**:
    - New `--profile` option to easily identify slowest tests.
    - Slowest tests displayed directly within the CLI output.

- **Pest test scaffolding**:
    - Opt-in feature for new Laravel projects.
    - Use `--pest` flag when creating a new application via the Laravel installer.

- **Generator CLI prompts**:
    - All built-in make commands no longer require any input.
    - Prompted for required arguments if invoked without input.

- **Horizon and Telescope UI updates**:
    - Fresh, modern look.
    - Improved typography, spacing, and design.

### Laravel version 11.0

**Laravel version 11.0 was released on March 14, 2024.** Some of its new features include:

- **Streamlined Application Structure**:
  - Revitalized `bootstrap/app.php` for code-first application configuration.
  - Reduced default service providers to a single `AppServiceProvider`.
  - Opt-in API and broadcast routing through Artisan commands.
  - Moved middleware into the framework itself for a leaner application structure.

- **Laravel Reverb**:
  - A scalable WebSocket server for real-time capabilities.
  - Supports horizontal scaling via Redis's publish/subscribe capabilities.

- **Per-Second Rate Limiting**:
  - Supports "per-second" rate limiting for HTTP requests and queued jobs.

- **Health Routing**:
  - A simple health-check endpoint at `/up` for monitoring services.

- **Graceful Encryption Key Rotation**:
  - Allows for previous encryption keys to be defined and used for decryption.

- **Prompt Validator Integration**:
  - Utilize Laravel's validator for validating prompt inputs.

- **Queue Interaction Testing**:
  - Simplified testing for released, deleted, or manually failed queued jobs.

- **New Artisan Commands**:
  - Commands for quick creation of classes, enums, interfaces, and traits.

- **Model Casts Improvements**:
  - Allows defining model's casts using a method for streamlined definitions.

- **The `once` Function**:
  - Caches the result of a callback in memory for the duration of the request.

- **Improved Performance When Testing With In-Memory Databases**:
  - Reuse of PHP's PDO object across connections for faster testing.

- **Improved Support for MariaDB**:
  - A dedicated MariaDB driver with better defaults.
