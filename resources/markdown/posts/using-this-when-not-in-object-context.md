---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/9/guy-coding-2_hbrpyv.jpg
Title: "Here's the fix to \"using $this when not in object context\""
Description: "Learn why the \"Using $this when not in object context\" error happens, and let me show you the only way to fix."
Published at: 2022-10-07
Modified at: 2022-12-14
Categories: php
---

## Introduction

To fix *"Using $this when not in object context"*, you can make the static method that is calling `$this` non-static.

No matter if you're using CodeIgniter, CakePHP, Laravel, Symfony, WordPress, Yii, or anything else, **`$this` is a variable that refers to the current object**. Therefore, it's natural to not being allowed to call it from a [static method](https://www.php.net/manual/en/language.oop5.static.php#language.oop5.static.methods).

## How to fix "Using $this when not in object context", by example

Take this code and try to run it. You will see *"Using $this when not in object context"* again.

```php
class Foo {
    public static function bar() {
        // This is bad because we are in a static method.
        $this->baz();
    }
    
    public function baz() {
    }
}

Foo::bar();
```

As you can see, we are trying to call `baz()`, which is a non-static method, from a static method.

As mentioned above, we need to:
1. Remove the `static` keyword from `bar()`'s declaration;
2. Create an instance of `Foo` and call `bar()` from there.

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

You could also make the baz method static depending on your initial intention:

```php
class Foo {
    public static function bar() {
        static::baz();
    }
    
    public static function baz() {
    }
}
```

