---
id: "01KKEW2778ZP3MF8EM39X8011H"
title: "The best PHP packages to use in 2025"
slug: "best-php-packages"
author: "benjamincrozat"
description: "A recommendation of the best packages to use in any PHP project in 2024."
categories:
  - "packages"
  - "php"
published_at: 2024-02-01T00:00:00+01:00
modified_at: 2025-06-29T22:00:00+02:00
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/LMGvbvlhOqk2Yqa.jpg"
sponsored_at: null
---
## Introduction

As a developer in the ever-evolving landscape of PHP, I've found myself relying directly or indirectly on a handful of remarkable packages that have significantly improved my coding efficiency and product quality. Let me share some of these gems with you.

## The best PHP packages to use

### Monolog - Versatile logging for PHP

![Monolog's official website](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/best-php-packages-67b74b809900516bec82.png/public)

[Monolog](https://github.com/seldaek/monolog) is a must-have. It's incredibly flexible, allowing you to send logs to various outputs like files, sockets, and databases. Its compatibility with the PSR-3 interface makes it a versatile choice for integrating logging into any PHP project. Whether you're managing error logs or system health data, Monolog will do its job well. It's such a great package that Laravel has adopted it as their default logging library.

### Carbon - Date and time the easy way

![Carbon's official website.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/best-php-packages-f8363fad581d8f6760a7.png/public)

Handling date and time in PHP is something we all have to do. And [Carbon](https://carbon.nesbot.com) makes it even easier. It extends PHP's [`DateTime`](https://www.php.net/manual/fr/class.datetime.php) class and offers a plethora of functionalities that simplify date-time management. What I appreciate the most is its human-readable syntax, making time manipulations and calculations a breeze.

### Flysystem - File storage made simple

![Flysystem's official website.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/best-php-packages-c1afb047c3e5b98a6052.png/public)

[Flysystem](https://flysystem.thephpleague.com/v3/docs/) is a file storage library that abstracts different file system types. Be it local storage or cloud storage like AWS S3, Flysystem provides a unified API to manage them all. This package has saved me from the hassle of handling storage differences when deploying across various environments. This package has also been adopted by Laravel.

### Faker - The master of fake data

![Faker's official website.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/best-php-packages-9209294dce01da7cc05c.png/public)

When it comes to testing or seeding databases with dummy data, [Faker](https://github.com/FakerPHP/Faker) is my go-to package. It's incredibly easy to generate any type of fake data, from names and addresses to lorem ipsum text. It supports multiple languages too, which is handy for localization testing. I personally don't know what I'd do without it.

### league/commonmark - A Markdown parser

![league/commonmark's official website](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/best-php-packages-39e32797b583d7db3d45.png/public)

Need to parse Markdown? The [league/commonmark](https://commonmark.thephpleague.com) package is a highly-efficient Markdown parser for PHP. It adheres to the CommonMark specification and is extendable, allowing you to add custom features. It's used by Laravel by default and therefore, the one I use on this blog. league/commonmark's code is well put together, making it hard to understand and extend. But when you finally get it, it's extremely powerful.

If this list sent you looking for what to build with those packages, these are the next PHP reads I would keep open:

- [Call the OpenAI API from PHP with less boilerplate](/openai-php-client)
- [See what PHP 8.5 changes before you upgrade](/php-85)
- [See what PHP 8.3 changed before you rely on it](/php-83)
- [Find a few PHP blogs worth keeping in your reading rotation](/best-php-blogs)
- [Check whether your PHP version is part of the problem](/check-php-version)
