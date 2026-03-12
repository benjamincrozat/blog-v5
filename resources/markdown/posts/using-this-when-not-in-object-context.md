---
id: "01KKEW27NHQYY1XXFYM35D9DGR"
title: "Fix \"using $this when not in object context\" now"
slug: "using-this-when-not-in-object-context"
author: "benjamincrozat"
description: "Learn why the \"Using $this when not in object context\" error happens, and let me show you the only way to fix."
categories:
  - "php"
published_at: 2022-10-07T00:00:00+02:00
modified_at: 2025-07-09T06:07:00+02:00
serp_title: "Fix \"using $this when not in object context\" now (2025)"
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/ljKO828nZiPNSl6.jpg"
sponsored_at: null
---
## Introduction

To fix *"Using $this when not in object context"*, simply avoid calling `$this` from a static context. `$this` always refers to the current object instance, so it's meaningless outside of object instances.

Whether you're using CodeIgniter, CakePHP, Laravel, Symfony, WordPress, Yii, or anything else, the logic behind this error remains the same. Let's see exactly why this happens and how to solve it easily.

## Why PHP throws "Using $this when not in object context"

PHP throws this error because `$this` is reserved for referencing the current object instance. Static methods, anonymous functions, or code executed globally (outside of a class context) don't have an object instance to reference. Therefore, `$this` simply doesn't exist in these contexts, and PHP rightfully complains.

## How to fix "Using $this when not in object context"

Here's an example that triggers the error:

```php
class Foo {
    public static function bar() {
        // Error: $this is invalid here.
        $this->baz();
    }

    public function baz() {
    }
}

Foo::bar();
```

We're calling the non-static method `baz()` from a static method `bar()` using `$this`. PHP won't allow this.

### Solution 1: Make your method non-static and instantiate the object

The best way to solve this error is usually to remove the `static` keyword, instantiate the class, and then call your method from an object instance:

```php
class Foo {
    public function bar() {
        $this->baz();
    }

    public function baz() {
    }
}

$foo = new Foo;
$foo->bar();
```

### Solution 2: Make the called method static

If you intended your methods to remain static, change your non-static method to static and use `self::` or `static::` instead of `$this`:

```php
class Foo {
    public static function bar() {
        static::baz();
    }

    public static function baz() {
    }
}

Foo::bar();
```

### Solution 3: Avoid `$this` outside of class context

If you see this error outside a class (e.g., a standalone script), simply remove `$this`. It only belongs inside an object-oriented context:

```php
// This triggers an error:
echo $this->value;

// Simply remove $this:
$value = "Hello";
echo $value;
```

### Solution 4: Binding `$this` correctly in closures

Anonymous functions (closures) lose the `$this` context unless explicitly bound. Here's how to fix this:

```php
class Foo {
    public $value = "Hello";

    public function bar() {
        $fn = function () {
            echo $this->value;
        };

        // Bind closure to current object instance.
        $fn = $fn->bindTo($this);
        $fn();
    }
}

$foo = new Foo;
$foo->bar();
```

## Quick tip: `self::` vs. `static::`

Use `self::` to reference static methods or properties defined in the current class explicitly. Use `static::` for late static binding, which allows referencing methods or properties that may be overridden by child classes.

## Conclusion

You now know exactly why and how "Using $this when not in object context" happens and how to fix it efficiently. Remember to keep your object contexts clear, and PHP will happily play along.

For further reading, check the official [PHP manual on static methods](https://www.php.net/manual/en/language.oop5.static.php).

Happy coding!

If you are cleaning up classic PHP object mistakes and want the rest of that mental model tighter, these are the next reads I would keep nearby:

- [Fix old-style constructors before PHP breaks them](/methods-same-name-class-constructors-future-version-php)
- [Understand exceptions before your next try/catch block](/php-exceptions)
- [Use Override when you want inheritance mistakes to fail loudly](/php-83-override-attribute)
