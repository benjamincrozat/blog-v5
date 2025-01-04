---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/220/LZtkGaaS3mnQXxCBhxVWOVfrezhHWA-metaQnVzaW5lc3MgdGVhbSBsb29raW5nIGZvciBuZXcgcGVvcGxlLmpwZw%3D%3D-.jpg
Title: "This is where your php.ini file is"
Description: "Discover the location of your php.ini file using two simple methods: the phpinfo() function or the command line."
Published at: 2023-11-02
Categories: php
---

Your *php.ini* file is the control center for setting up your environment. Here are straightforward methods to locate this file.

## phpinfo() tells you where your php.ini file is

The quickest path any PHP developer discovers first is through the `phpinfo()` function. It’s a simple process:

- Create a new project with a PHP file named *index.php*.
- Add the following code:
```php
<?php
phpinfo();
?>
```
- Open the project in your web browser.

The "Loaded Configuration File" section will indicate where the active php.ini file is.

![phpinfo() in action.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/216/conversions/JAyCkwTofAYGl4PX3byMXdOJ8DUTcQ-metaQ2xlYW5TaG90IDIwMjMtMTEtMDIgYXQgMTcuMDQuNTBAMngucG5n--medium.jpg)

## phpinfo() can also be used used in the command line

The equivalent of the `phpinfo()` function in the command line is the `php -i` command. Run it, and you will get this kind of output:

```
phpinfo()
PHP Version => 8.3.0

System => Darwin BenjBook-Pro-14.local 23.1.0 Darwin Kernel Version 23.1.0: Mon Oct  9 21:27:24 PDT 2023; root:xnu-10002.41.9~6/RELEASE_ARM64_T6000 arm64
Build Date => Nov 21 2023 14:40:35
Build System => Darwin Sonoma-arm64.local 23.0.0 Darwin Kernel Version 23.0.0: Thu Aug 17 21:24:15 PDT 2023; root:xnu-10002.1.11~3/RELEASE_ARM64_VMAPPLE arm64
…
Loaded Configuration File => /opt/homebrew/etc/php/8.3/php.ini
```

Neat, right? But we can do better.

## The command line can also pinpoint your php.ini file

For those who favor the command line, PHP provides a straightforward command:
1. Open the terminal.
2. Type `php --ini` and execute it.
3. The terminal will display the path to the *php.ini* file.

![Using a terminal to run the php --ini command.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/218/conversions/IdaI8uqocUu45gmvwzENy2KOGSiec2-metaQ2xlYW5TaG90IDIwMjMtMTEtMDIgYXQgMTcuMTIuNTRAMngucG5n--medium.jpg)
