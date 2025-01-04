---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/266/01HFY51YWYW41RSPYZHEG3SDKY.jpg
Title: "PHP 8.3's Override attribute in a nutshell"
Description: "Discover a neat new addition to PHP 8.3 that will help express your intent: the Override attribute."
Published at: 2023-11-23
Categories: php
---

## What does the Override attribute do?

When you are using PHP 8.3's new [`Override`](https://wiki.php.net/rfc/marking_overriden_methods) attribute, here's what you tell other developers or your future self:
- **I'm overriding a method** from the parent class or one of the implemented interfaces.
- **I have correctly overridden the method.** If, for instance, I made a typo, the code would throw an error.

I had so much trouble understanding this one. But in fact, it's **dead simple**. 😅

Here's an example:

```php
class SomeClass
{
	public function someMethod()
	{
	}
}

class SomeOtherClass extends SomeClass
{
	public function someMethod()
	{
	}
}
```

In this example, `SomeOtherClass` overrides the `someMethod` method from its parent class, `SomeClass`. But what happens if we change `someMethod` in `SomeClass`? Our override will be completely nullified.

But as you guessed it, the solution to this problem is the `Override` attribute:

```php
class SomeClass
{
	// We renamed the method.
	public function someRenamedMethod()
	{
	}
}

class SomeOtherClass extends SomeClass
{
	// But now, thanks to Override, we get an error message saying
	// that our override doesn't override any method anymore.
	//
	// Fatal error: SomeOtherClass::someMethod() has #[\Override] attribute, but no matching parent method exists
	
	#[Override]
	public function someMethod()
	{
	}
}
```

How neat is that? 👌

## When to use Override

Let's look at some scenarios where `Override` is incredibly useful:

- **Accidental method names**: Imagine you're working on a class that extends another. You add a new method, thinking you're overriding a parent method. But, oops, you got the method name wrong. Without `Override`, PHP wouldn't warn you, leading to potential issues. With `Override`, PHP immediately alerts you to the mistake.
- **Interface changes**: You have a class implementing an interface. Later, the interface changes, maybe removing or renaming a method. Without `Override`, you might not realize your class no longer correctly implements the interface. With the attribute in place, PHP would flag this change, prompting you to update your class.

## Why did the PHP team choose the Override attribute over a new keyword?

You might wonder why the people working on PHP used the `Override` attribute instead of a new keyword (like `final` and `readonly` for instance). The reason is twofold:
- **Backward compatibility**: Using an attribute doesn't require changes to the PHP parser, making it backward compatible. You can add it to existing codebases without issues.
- **Non-behavioral**: The attribute doesn't change how the method works or is called. It's purely for validation during development, making it less intrusive.

## Limitations of Override and its future

Currently, `Override` is limited to methods. Properties, another key part of classes, don't yet have a similar feature. However, the future might bring more enhancements, like class-level attributes or directives to enforce the use of `Override`.
