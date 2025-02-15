---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/279796cc-c507-49c0-aa57-2a1188ab0720
Title: "The best PHP packages to use in 2024"
Description: "A recommendation of the best packages to use in any PHP project in 2024."
Published at: 2024-02-01
Categories: packages, php
---

## Introduction

As a developer in the ever-evolving landscape of PHP, I've found myself relying directly or indirectly on a handful of remarkable packages that have significantly improved my coding efficiency and product quality. Let me share some of these gems with you.

## The best PHP packages to use

### Monolog - Versatile logging for PHP

![Monolog's official website](https://res.cloudinary.com/benjamincrozat-com/image/fetch/https://github.com/benjamincrozat/content/assets/3613731/edfb94d7-1945-4d4c-ac82-222554a97804)

[Monolog](https://github.com/seldaek/monolog) is a must-have. It's incredibly flexible, allowing you to send logs to various outputs like files, sockets, and databases. Its compatibility with the PSR-3 interface makes it a versatile choice for integrating logging into any PHP project. Whether you're managing error logs or system health data, Monolog will do its job well. It's such a great package that Laravel has adopted it as their default logging library.

### Carbon - Date and time the easy way

![Carbon's official website.](https://res.cloudinary.com/benjamincrozat-com/image/fetch/https://github.com/benjamincrozat/content/assets/3613731/83c76604-a4ef-4bc2-970e-d7a74e0cb1fc)

Handling date and time in PHP is something we all have to do. And [Carbon](https://carbon.nesbot.com) makes it even easier. It extends PHP's [`DateTime`](https://www.php.net/manual/fr/class.datetime.php) class and offers a plethora of functionalities that simplify date-time management. What I appreciate the most is its human-readable syntax, making time manipulations and calculations a breeze.

### Flysystem - File storage made simple

![Flysystem's official website.](https://res.cloudinary.com/benjamincrozat-com/image/fetch/https://github.com/benjamincrozat/content/assets/3613731/a3fe807b-d100-4a05-b4e8-d26b3b1abbc7)

[Flysystem](https://flysystem.thephpleague.com/v3/docs/) is a file storage library that abstracts different file system types. Be it local storage or cloud storage like AWS S3, Flysystem provides a unified API to manage them all. This package has saved me from the hassle of handling storage differences when deploying across various environments. This package has also been adopted by Laravel.

### Faker - The master of fake data

![Faker's official website.](https://res.cloudinary.com/benjamincrozat-com/image/fetch/https://github.com/benjamincrozat/content/assets/3613731/871d37d4-5847-409d-9841-e6af8ea42616)

When it comes to testing or seeding databases with dummy data, [Faker](https://github.com/FakerPHP/Faker) is my go-to package. It's incredibly easy to generate any type of fake data, from names and addresses to lorem ipsum text. It supports multiple languages too, which is handy for localization testing. I personally don't know what I'd do without it.

### league/commonmark - A Markdown parser

![league/commonmark's official website](https://res.cloudinary.com/benjamincrozat-com/image/fetch/https://github.com/benjamincrozat/content/assets/3613731/2193b39d-ad54-45f9-a054-bf2defd11ab3)

Need to parse Markdown? The [league/commonmark](https://commonmark.thephpleague.com) package is a highly-efficient Markdown parser for PHP. It adheres to the CommonMark specification and is extendable, allowing you to add custom features. It's used by Laravel by default and therefore, the one I use on this blog. league/commonmark's code is well put together, making it hard to understand and extend. But when you finally get it, it's extremely powerful.
