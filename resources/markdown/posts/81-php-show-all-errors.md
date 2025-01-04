---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/190/DP8uQOKxgSB13dgrgkAeVyUPpoYS1B-metaZXJyb3JzLmpwZw%3D%3D-.jpg
Title: "Easily show all errors in PHP"
Description: "Discover how to reveal all PHP errors in your script or globally via php.ini for effective debugging, but remember to adjust before going live."
Published at: 2023-10-07
Categories: php
---

## Show all PHP errors using ini_set() and error_reporting()

**To showcase all errors in PHP, include these lines at the top of your PHP file:**

```php
ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
```

By tweaking these settings using the [ini_set()](https://www.php.net/ini_set) and [error_reporting()](https://www.php.net/error_reporting) functions, PHP will now show all runtime errors, startup errors, and even notify you about potential issues.

Let's decode what all this means:

- `display_errors`: This key is a directive which determines whether errors should be printed as a part of the program's output or hidden. We set it to `1` to instruct PHP to display errors.
  
- `display_startup_errors`: This directive decides if PHP will show errors that occur during PHP's startup sequence. We set `display_startup_errors` to `1`, so PHP clearly communicates any startup issues.
  
- `error_reporting(E_ALL)`: This setting controls the level of error reporting. `E_ALL` is a constant that instructs PHP to show all possible errors, warnings, and notices.

## How to adjust the setting in your php.ini to show all errors

Besides adding these values in your PHP script, you can also set them globally from the `php.ini` file. Here are the steps to find and edit this file:

1. Open a command line, and run `php --ini`. If PHP is installed correctly, this command will output the location of the `php.ini` file.
2. Open `php.ini` in your preferred text editor. Scroll through the file or do a quick search for `display_errors`, `display_startup_errors`, and `error_reporting`.
3. Set the values as we did in our script - `display_errors` and `display_startup_errors` to `1`, and `error_reporting` to `E_ALL`.
  
**Remember to save your changes.** And if you don't want to shout at your computer because nothing is happening, **restart your PHP server to apply them**.

## Don't display all PHP errors in production

Altering these settings can be very useful for debugging during development, but beware that **you must not do it in the context of a live application**.

Having PHP show all errors can give malicious users more information about your server configuration and potential attack routes.

Therefore, always ensure production-safe settings values before deploying. As a safer alternative in production, consider logging errors to a non-publicly accessible file that you can review later. Set `log_errors = on` and provide a file path for `error_log` in the `php.ini` file for this purpose.
