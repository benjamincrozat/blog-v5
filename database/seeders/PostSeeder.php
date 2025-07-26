<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Jobs\FetchImageForPost;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run() : void
    {
        Post::factory(100)
            ->recycle(User::all())
            ->recycle(Category::all())
            ->create([
                'content' => <<<'MARKDOWN'
# Heading 1

## Heading 2

### Heading 3

#### Heading 4

##### Heading 5

###### Heading 6

**Bold text**

*Italic text*

~~Strikethrough~~

[Link to Google](https://www.google.com)

![Alt text for image](https://via.placeholder.com/150)

> Blockquote example
>
> - Nested list in blockquote

- Unordered list item 1
- Unordered list item 2
  - Nested item

1. Ordered list item 1
2. Ordered list item 2

- [x] Task list complete
- [ ] Task list incomplete

---

`Inline code`

```
// Example: Simple Hello World in multiple languages
function hello() {
    return "Hello, world!";
}

for (let i = 0; i < 3; i++) {
    console.log(hello());
}

// Multi-line comment
/*
This is a longer code block
with several lines and a loop.
*/
```

```php
<?php
// Example: PHP class with method and docblock
/**
 * Class Greeter
 * Prints a greeting message.
 */
class Greeter {
    public function sayHello(string $name): string
    {
        return "Hello, $name!";
    }
}

$greeter = new Greeter();
echo $greeter->sayHello('World');

// Array manipulation
$numbers = [1, 2, 3, 4, 5];
$squared = array_map(fn($n) => $n * $n, $numbers);
print_r($squared);
```

```js
// Example: JavaScript async function and array methods
async function fetchData(url) {
    try {
        const response = await fetch(url);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

const numbers = [1, 2, 3, 4, 5];
const doubled = numbers.map(n => n * 2);
console.log(doubled);

fetchData('https://api.example.com/data').then(data => {
    console.log('Fetched:', data);
});
```

| Table | Example |
|-------|---------|
| Cell  | Cell    |
| Cell  | Cell    |

<!-- HTML comment -->

MARKDOWN
            ])
            ->each(function (Post $post) {
                FetchImageForPost::dispatch($post);

                $post->categories()->attach(
                    Category::query()->inRandomOrder()->limit(random_int(1, 3))->get()
                );
            });
    }
}
