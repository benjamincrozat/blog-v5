---
id: "01KKEW27KGZ8NW1EWWY600MDNA"
title: "PHP dynamic method call dangers"
slug: "php-dynamic-method-call-dangers"
author: "benjamincrozat"
description: "Learn why PHP dynamic method calls are risky and how to fix them with safe mappings and allowlists."
categories:
published_at: 2025-10-06T08:10:00+02:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: null
image_path: null
sponsored_at: null
---
Dynamic method calls in PHP feel neat, but they can bite.

Ash Allen shows how letting user input pick a method like `$obj->$name()` can open doors you did not mean to open. Think surprise deletes. Think hidden debug paths.

What I liked most is the simple fix. Do not call methods straight from user input. Map input to safe actions instead.

Bad

```php
$action = $_GET['action'];
$controller->$action();
```

Better

```php
$action = $_GET['action'] ?? '';
$map = [
  'index' => 'showIndex',
  'store' => 'storePost',
];

if (!isset($map[$action])) {
  http_response_code(404);
  exit;
}

$controller->{$map[$action]}();
```

He also reminds us to use allowlists, check `is_callable`, and avoid magic catch‑alls like `__call` for user input paths.

If you ever map routes or commands in PHP, this is a quick read that can save a long night of bugs and security headaches.

If you are cleaning up risky dynamic PHP patterns before they bite you, these are the next reads I would keep nearby:

- [Methods with the same name as their class will not be constructors in a future version of PHP](/methods-same-name-class-constructors-future-version-php)
- [Fix "using $this when not in object context" now](/using-this-when-not-in-object-context)
- [PHP exceptions: all you need to know](/php-exceptions)
