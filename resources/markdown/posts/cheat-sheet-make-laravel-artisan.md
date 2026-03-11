---
id: "01KKEW277TAFNXGFP9MNPRRBJQ"
title: "A cheat sheet for Laravel's make Artisan commands"
slug: "cheat-sheet-make-laravel-artisan"
author: "benjamincrozat"
description: "Let's take a look at all the things Laravel lets you create through its Artisan commands."
categories:
published_at: null
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/jcnqm2I2GzwNMph.jpg"
sponsored_at: null
---
## Introduction

## php artisan make:cast

I often find the need to create custom Eloquent casts. These casts allow me to define how attributes in a model are stored in and fetched from the database. The `php artisan make:cast` command helps me swiftly generate a new custom Eloquent cast class, ensuring I don't have to write boilerplate code every time.

### Arguments

- `name`: The name you want for your custom cast class.

### Options

- `-f, --force`: Sometimes you might realize that you want to overwrite an existing cast. This option ensures that the cast class is created, even if one with the same name already exists.
- `--inbound`: If you're focusing on customizing how a model's attribute is stored in the database, this option helps you generate an inbound cast class.

## php artisan make:channel

The `php artisan make:channel` command is instrumental when you need to quickly scaffold a new channel class. Channel classes in Laravel are used for defining new channels for event broadcasting.

### Arguments

- `name`: The name you desire for your channel class.

### Options

- `-f, --force`: If you're certain about your actions and want to overwrite an existing channel, this option ensures the channel class is generated even if a channel with the same name already exists.

## php artisan make:command

The `php artisan make:command` streamlines the process of creating a new Artisan command. Artisan commands facilitate various tasks in Laravel, allowing developers to automate repetitive actions through the console.

### Arguments

- `name`: The name you want for your Artisan command.

### Options

- `-f, --force`: If you need to overwrite an existing console command, this option ensures the command class is created regardless of existing commands with the same name.
- `--command[=COMMAND]`: Specify the terminal command that you'll use to call this class.
- `--test`: When practicing TDD, this option is invaluable as it creates a PHPUnit test alongside your console command.
- `--pest`: If you prefer Pest for testing, use this to generate a corresponding Pest test for your console command.

## php artisan make:view

One of the most common tasks is creating a new view for my application. Instead of manually navigating to the views directory and creating files, I use the `php artisan make:view` command. It's a timesaver, especially when I want to quickly scaffold a view and even generate accompanying tests.

### Arguments

- `name`: The name you want for your new view.

### Options

- `--extension[=EXTENSION]`: This option lets you specify the extension for the generated view. By default, it uses "blade.php", but it's handy if you ever need a different extension for any reason.
- `-f, --force`: There might be times when you accidentally try to create a view that already exists. Using the `--force` option ensures the view is created even if it already exists. It's like saying, "You know what you're doing, just overwrite it!"
- `--test`: This is a fantastic option when you're practicing TDD (Test Driven Development). It automatically generates a PHPUnit test alongside your view, saving you a step.
- `--pest`: Similarly, if you're using Pest for testing, this option generates a corresponding Pest test for the view. It's all about making your testing process smoother.