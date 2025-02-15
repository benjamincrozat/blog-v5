---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/43/2181063_k8up0j.png
Title: "Enums in PHP: a guide to safer coding"
Description: "Let's step up your code with a safer way of coding using PHP's Enumerations, or Enums. With this guide, you'll know everything there is to know about them."
Published at: 2023-07-05
Modified at: 2023-07-08
Categories: php
---

## Introduction to Enums in PHP

As a developer, you must have come across situations where a variable could only take one out of a small set of possible values. For instance, a variable holding a user's status might only have the possibilities "Active", "Inactive", or "Suspended". You could represent these states using separate boolean variables or assign them specific string or integer values, but that's where things start getting messy and error-prone.

Wouldn't it be nice to have a way of declaring this restricted set of possible values in a clear, self-documenting manner?  That's exactly where Enumerations, or Enums, as they are often called, come to the rescue.

Enumerations have been part of many programming languages for years and I was so envious! They allow you to define a type that is restricted to a specific set of values, enhancing both clarity and safety. For PHP developers, the great news is that as of PHP 8.1, Enums are now part of the PHP core. Yes, you heard it right! PHP now provides built-in support for Enums. 🎉

## A brief history of Enums in PHP

Before PHP 8.1, PHP did not have built-in support for Enums. While this lack of Enums did not prevent developers from writing code, it did mean that PHP lacked a tool found in many other languages that can make coding safer and more efficient.

Without built-in Enums, developers had to use other constructs to represent a set of possible values, often resorting to class constants, arrays, or sometimes just strings or integers.

However, this could lead to potential errors and often resulted in code that was not as clear as it could be.

The introduction of Enums in PHP 8.1 has been a significant addition to the language.

PHP's implementation of Enums is more powerful than many other languages, as Enums in PHP are not merely integer or string values under the hood.

In PHP, an Enum is a special kind of object, with cases of the Enum being single-instance objects of that class.

This means you can use Enums anywhere you could use an object, making them extremely versatile.

The addition of Enums to PHP signifies the language's ongoing evolution to incorporate more modern programming concepts and paradigms, increasing its efficiency, and enabling developers to write safer and cleaner code.

## Understanding the basics of Enums in PHP

To unravel the basics of Enums in PHP, I decided to use the magical world of Harry Potter.

Imagine we're coding for the Hogwarts School of Witchcraft and Wizardry, where each new student must be sorted into a house by the virtual Sorting Hat.

At Hogwarts, there are only four houses: Gryffindor, Hufflepuff, Ravenclaw, and Slytherin.

So, we could represent this as an Enum in PHP.

Here's how you declare an Enum:

```php
<?php

enum House
{
    case Gryffindor;
    case Hufflepuff;
    case Ravenclaw;
    case Slytherin;
}
```

In this example, we have created an Enum named House, which can have four possible values - `Gryffindor`, `Hufflepuff`, `Ravenclaw`, and `Slytherin`.

Let's create a `Student` class, where each student holds a `$house` property.

```php
class Student
{
    public ?House $house = null;
}
```

We also have a `SortingHat` class with a `sort` method.

This method either accepts a house suggestion or randomly assigns a house to the student.

Just like in Harry Potter, the Sorting Hat took Harry's choice into consideration!

```php
class SortingHat
{   
    public function sort(Student $student, ?House $suggestedHouse = null)
    {
        if ($suggestedHouse) {
            $student->house = $suggestedHouse;
            
            return;
        }

        $houses = [
            House::Gryffindor,
            House::Hufflepuff,
            House::Ravenclaw,
            House::Slytherin,
        ];

        $index = array_rand($houses);
        
        $student->house = $houses[$index];
    }
}
```

As you can see, we've limited the values of the `$suggestedHouse` variable and `$house` property to only be one of the four Hogwarts houses using the `House` Enum.

If you were to try to sort a student into a non-existent house, PHP will catch this, and it wouldn't run.

That's the magic of Enums!

In this scenario, the Hogwarts houses with no associated data are called "Pure Cases," and an Enum that contains only Pure Cases, like our House Enum, is referred to as a "Pure Enum."

## Pure Enums VS. Backed Enums

Backed Enums are Enums backed with a scalar value. That's why they are considered "not pure."

For a reminder, a scalar value in PHP is either of type `bool`, `float`, `int` or `string`.

Code speaks more than words sometimes, so here's a Backed Enum:

```php
<?php

enum House: string
{
    case Gryffindor = 'Gryffindor';
    case Hufflepuff = 'Hufflepuff';
    case Ravenclaw = 'Ravenclaw';
    case Slytherin = 'Slytherin';
}
```

As you can see, we defined the type that backs the Enum (`string` in that case) and we assign a value to each case. Couldn't be simpler than that. But why would you use Backed Enums?

Here's a great use case:

```php
// Let's pretend we fetched this value from the database.
$house = 'Gryffindor';

$student = new Student(
    House::from($house)
);

// object(Student)#1 (1) {
//   ["house"]=>
//   enum(House::Gryffindor)
// }
var_dump($student);
```

In this example:
1. We pretend we fetched data from a database and we try to create a new `Student` object.
2. We initialize the `$house` property with the `House::from()` static method from a string value (since `House` is now a backed enum of type `string`).
3. If this fails, an exception is throw. (`Uncaught ValueError: "Foo" is not a valid backing value for enum "House"`)

In some cases, instead of throwing an exception, you might want to fall back on a default value. Here's how you can do that with the `House::tryFrom()` static method, which returns `null` in case of failure.

```php
$student = new Student(
    House::tryFrom('Slytherin') ?? House::Gryffindor
);
```

## Listing Enum values

You saw in the previous example that without a suggestion from the student, a house is randomly assigned.

What bothers me in the example I provided, though, is that we manually listed the possible values of the `House` Enum to build our array.

Fortunately, Enums all have a `cases()` method that can make our code more flexible!

```php
class SortingHat
{   
    public function sort(Student $student, ?House $suggestedHouse = null)
    {
        if ($suggestedHouse) {
            $student->house = $suggestedHouse;
            
            return;
        }

        {-$houses = [-}
        {-    House::Gryffindor,-}
        {-    House::Hufflepuff,-}
        {-    House::Ravenclaw,-}
        {-    House::Slytherin,-}
        {-];-}
        {+$houses = House::cases();+}

        $index = array_rand($houses);
        
        $student->house = $houses[$index];
    }
}
```

Pretty neat, right?

## A deep dive into PHP Enums and their comparison with classes

In our journey with PHP Enums so far, you might have noticed that they are similar to classes.

They can be namespaced, implement interfaces, use traits, and they are also autoloadable in the same way.

But, of course, there are also some significant differences between PHP classes and Enums.

Let's revisit our magical Harry Potter example to understand these differences.

When a student is sorted into a house by the Sorting Hat, we know that the house will always be one of the four predefined options - `Gryffindor`, `Hufflepuff`, `Ravenclaw`, or `Slytherin`. 

There are no other possibilities in the context of Hogwarts. This scenario is perfect for Enums.

An Enum, like `House`, is a unique data type that comprises a set of predefined constants.

It signifies that a variable can have only one of these predefined constants and nothing else.

For example, the `$house` property of the `Student` class can only hold one of the four house options defined in the `House` Enum.

```php
class Student
{
    public ?House $house = null;
}
```

In contrast, classes in PHP, like our `Student` class, can hold a variety of different properties and can be instantiated multiple times with different property values.

Enums, on the other hand, cannot be instantiated and are used to define a fixed, limited set of instances.

Another difference is in the way we compare classes and Enums. 

In PHP, Enums are compared by their identity, not by their values.

Let's take a look at an example:

```php
$a = House::Gryffindor;
$b = House::Gryffindor;

var_dump($a === $b); // bool(true)
```

In this example, `$a` and `$b` are the same `House` Enum instance, so `$a === $b` is `true`.

Comparisons using less than `<` or greater than `>` operators are not meaningful for Enum objects and will always return false.

Enum cases in PHP have a special property called `name`, which is the case-sensitive name of the case itself. This can be useful when you want to print the name of the Enum case.

```php
echo House::Gryffindor->name; // Prints "Gryffindor".
```

## Working with Enumeration methods

Now that we've explored how Enums can be compared and their differences from classes, it's time to dive deeper and explore enumeration methods in PHP Enums.

Just like classes, Enums in PHP can contain methods.

Let's see how we can utilize this feature using our Harry Potter sorting scenario.

```php
enum House
{
    case Gryffindor;
    case Hufflepuff;
    case Ravenclaw;
    case Slytherin;

    public function getHouseColors() : array
    {
        return match($this) {
            House::Gryffindor => ['Red', 'Gold'],
            House::Hufflepuff => ['Yellow', 'Black'],
            House::Ravenclaw => ['Blue', 'Bronze'],
            House::Slytherin => ['Green', 'Silver'],
        };
    }
}

// array(2) {
//   [0]=>
//   string(3) "Red"
//   [1]=>
//   string(4) "Gold"
// }
var_dump(House::Gryffindor->getHouseColors());
```

In the magical world of Hogwarts, every house has its colors. In our example above, we've added a `getHouseColor()` method to our `House` Enum to return the color of each house.

When a method is defined within an Enum, the `$this` variable is defined and refers to the case instance.

## Enums can use Traits and implement Interfaces

Like classes, Enums can use Traits. This is great when there are a lot of methods and you need to split them across multiple files to keep your code tidier.

There are some restrictions, though:
- You can't have properties.
- You can't override Enums methods (like `values()`).

```php
trait Colors
{
    public function getHouseColors() : array
    {
        return match($this) {
            House::Gryffindor => ['Red', 'Gold'],
            House::Hufflepuff => ['Yellow', 'Black'],
            House::Ravenclaw => ['Blue', 'Bronze'],
            House::Slytherin => ['Green', 'Silver'],
        };
    }
}

enum House
{
    use Colors;
  
    case Gryffindor;
    case Hufflepuff;
    case Ravenclaw;
    case Slytherin;
}
```

Like like Traits in Classes, Interfaces can also be implemented into Enums. It's difficult to find a concrete example for this use case, but here you go anyway:

```php
interface HasColors
{
    public function getHouseColors() : array;
}

enum House implements HasColors
{
    case Gryffindor;
    case Hufflepuff;
    case Ravenclaw;
    case Slytherin;
  
    public function getHouseColors() : array
    {
        return match($this) {
            House::Gryffindor => ['Red', 'Gold'],
            House::Hufflepuff => ['Yellow', 'Black'],
            House::Ravenclaw => ['Blue', 'Bronze'],
            House::Slytherin => ['Green', 'Silver'],
        };
    }
}
```

## What about Enums in PHP 7 or even PHP 5?

Before the introduction of native Enumerations in PHP 8.1, enums were typically handled in PHP in a few different ways, none of which were particularly elegant or reliable. Here are some of the common strategies:

- **Class constants:** Perhaps the most common way of implementing enums was through class constants. Here's an example:

```php
class House
{
    const Gryffindor = 'Gryffindor';
    const Hufflepuff = 'Hufflepuff';
    const Ravenclaw = 'Ravenclaw';
    const Slytherin = 'Slytherin';
}
```

In this way, you could refer to an enum value with House::Gryffindor, for instance. This approach provides a way to group related constants, but doesn't provide any of the type safety or functionality that true enums might offer (because class constants cannot be typed and marked as readonly).

- **Arrays:** Another common way was to use arrays to hold possible values.

```php
$houses = [
    'Gryffindor', 
    'Hufflepuff',
    'Ravenclaw',
    'Slytherin',
];
```

However, this method also lacks the benefits of true Enums such as type safety and autocompletion.

- **[SplEnum](http://php.adamharvey.name/manual/en/class.splenum.php):** PHP used to have a built-in SplEnum class, which was a part of the Standard PHP Library (SPL). However, it was not widely adopted due to its performance issues and it required the SPL Types extension which was not bundled with PHP and was considered experimental.

```php
class House extends SplEnum
{
    const Gryffindor = 'Gryffindor';
    const Hufflepuff = 'Hufflepuff';
    const Ravenclaw = 'Ravenclaw';
    const Slytherin = 'Slytherin';
}
```

This class was removed in PHP 7.0, so it's not used anymore.

- **Third-party packages:** There are also several packages that provide enum functionality, such as [myclabs/php-enum](https://packagist.org/packages/myclabs/php-enum). These often provide more features than simple class constants or arrays, such as methods for listing all possible values, converting to/from strings, etc.

```php
use MyCLabs\Enum\Enum;

class House extends Enum
{
    const Gryffindor = 'Gryffindor';
    const Hufflepuff = 'Hufflepuff';
    const Ravenclaw = 'Ravenclaw';
    const Slytherin = 'Slytherin';
}
```

Despite these workarounds, none of them could provide the full set of features that true Enumerations can offer, such as type safety, performance optimization, and functionality like getting all possible values.

This is why the addition of native Enumerations in PHP 8.1 was such an important upgrade for the language.
