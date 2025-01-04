---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/59/php-83_p6vkhz.png
Title: "6 ways to check which version of PHP you are running"
Description: "Discover how to check your version of PHP using phpinfo(), your terminal, Laravel's welcome page, or a Laravel Artisan command."
Published at: 2023-09-02
Categories: php, laravel
---

## The quickest way to check your version of PHP

**To check your version of PHP, simply run the `php -v` command**, no matter if you are running macOS, Linux, or Windows. 

## 6 ways to check your version of PHP

### Using phpversion()

![Checking the version of PHP using phpversion.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/170/conversions/CleanShot_2023-09-02_at_16.49.10_2x_z0shyv-medium.jpg)

**To check which version of PHP you are running, you use the [`phpversion()`](https://www.php.net/phpversion) function.** It returns a single string containing the precious information.

```php
<?php echo phpversion(); ?>
```

### Using phpinfo()

![Checking the version of PHP using phpinfo.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/171/conversions/CleanShot_2023-09-02_at_16.17.14_2x_gkxt9j-medium.jpg)

**To check which version of PHP you are running, use the [`phpinfo()`](https://www.php.net/phpinfo) function.** It will show you a web page containing every bit of information you might need about your installation of PHP.

```php
<?php phpinfo(); ?>
```

### Using the terminal on macOS, Linux and WSL

![Checking the version of PHP using the terminal on macOS and Linux.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/172/conversions/CleanShot_2023-09-02_at_16.20.15_2x_yobi06-medium.jpg)

**To check which version of PHP you are running using your terminal on macOS or Linux, use the `php -v` command.** It's simple and straightforward, the version of PHP is the first thing in the output.

```bash
php -v
```

### Using the command prompt on Windows

**To check which version of PHP you are running using your Windows command prompt, use the `php -v` command.** It's as simple and straightforward as in the previous section.

```batch
php -v
```

### Using Laravel's welcome page

![Checking the version of PHP using Laravel's homepage.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/173/conversions/CleanShot_2023-09-02_at_16.13.39_2x_nz0b7t-medium.jpg)

**To check which version of PHP you are running using Laravel's welcome page, just look at the bottom right corner.** It's that simple.

### Using Laravel Artisan

![Checking the version of PHP using Laravel Artisan.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/174/conversions/CleanShot_2023-09-02_at_16.14.00_2x_klstth-medium.jpg)

**To check which version of PHP you are running using Laravel, use the `php artisan about` command.** It will show you the version of PHP, including various information about your setup.

```bash
php artisan about
```
