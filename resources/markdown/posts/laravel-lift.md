---
id: "01KKEW27CQ0QMCG7X8M8P8H51D"
title: "Make your Eloquent models IDE-friendly using Laravel Lift"
slug: "laravel-lift"
author: "benjamincrozat"
description: "Check out Laravel Lift, a package that enhances Eloquent Models and makes them more IDE-friendly - all thanks to Wendell Adriel."
categories:
  - "laravel"
  - "packages"
published_at: 2023-08-29T00:00:00+02:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: "https://github.com/WendellAdriel/laravel-lift"
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/oFzpJlrCT8Er8js.png"
sponsored_at: null
---
Laravel Lift is a fantastic package that boosts your Eloquent Models in Laravel.
 
Created by [Wendell Adriel](https://wendelladriel.com), Lift lets you create public properties in Eloquent models that match your table schema. This makes them easier to read and work with in any IDE.

The package intelligently uses PHP 8's attributes, and gives you complete freedom in setting up your models. For instance, you can put validation rules right into your models - a simple and easy-to-understand arrangement compared to a separate request class. Plus, all these settings are easily reachable through handy new methods.
 
With a focus on simplicity, Lift depends on Eloquent events to work. This means the package fits easily into your project, without needing any major changes (unless you've turned off event triggering).