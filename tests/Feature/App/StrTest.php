<?php

use App\Str;

it('adds IDs to headings', function () {
    $markdown = '# Foo';

    $html = Str::markdown($markdown);

    expect($html)->toContain('id="foo"');
});

it('disallows some HTML', function (string $input) {
    $html = Str::markdown($input);

    expect($html)->toContain('&lt;');
})->with([
    '<noembed>foo</noembed>',
    '<noframes>foo</noframes>',
    '<plaintext>foo</plaintext>',
    '<script>alert("foo")</script>',
    '<style>body { color: red }</style>',
    '<textarea>foo</textarea>',
    '<title>foo</title>',
    '<xmp>foo</xmp>',
]);

it('opens external links in new windows', function () {
    $markdown = 'https://example.com';

    $html = Str::markdown($markdown);

    expect($html)->toContain('target="_blank"');
});

it('converts quotes to smart quotes', function () {
    $markdown = '"';

    $html = Str::markdown($markdown);

    expect($html)->toContain('“');
});

it('renders code blocks with syntax highlighting', function () {
    $markdown = <<<'MD'
```php
$user = User::find(1);
```
MD;

    $html = Str::markdown($markdown);

    expect($html)->toContain('<pre data-lang="php" class="notranslate">');
});

it('renders inline code', function () {
    $markdown = '`some inline code`';

    $html = Str::markdown($markdown);

    expect($html)->toContain('<code>some inline code</code>');
});
