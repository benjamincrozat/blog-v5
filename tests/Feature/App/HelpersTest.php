<?php

it('extracts headings from markdown', function () {
    $markdown = <<<'MARKDOWN'
# Foo
## Bar
### Baz
#### Lorem
#### Ipsum
#### Dolor
#### Sit
#### Amet
MARKDOWN;

    $headings = extract_headings_from_markdown($markdown);

    expect($headings[0]['level'])->toBe(1);
    expect($headings[0]['text'])->toBe('Foo');
    expect($headings[0]['slug'])->toBe('foo');

    expect($headings[0]['children'][0]['level'])->toBe(2);
    expect($headings[0]['children'][0]['text'])->toBe('Bar');
    expect($headings[0]['children'][0]['slug'])->toBe('bar');

    expect($headings[0]['children'][0]['children'][0]['level'])->toBe(3);
    expect($headings[0]['children'][0]['children'][0]['text'])->toBe('Baz');
    expect($headings[0]['children'][0]['children'][0]['slug'])->toBe('baz');

    expect($headings[0]['children'][0]['children'][0]['children'][0]['level'])->toBe(4);
    expect($headings[0]['children'][0]['children'][0]['children'][0]['text'])->toBe('Lorem');
    expect($headings[0]['children'][0]['children'][0]['children'][0]['slug'])->toBe('lorem');

    expect($headings[0]['children'][0]['children'][0]['children'][1]['level'])->toBe(4);
    expect($headings[0]['children'][0]['children'][0]['children'][1]['text'])->toBe('Ipsum');
    expect($headings[0]['children'][0]['children'][0]['children'][1]['slug'])->toBe('ipsum');

    expect($headings[0]['children'][0]['children'][0]['children'][2]['level'])->toBe(4);
    expect($headings[0]['children'][0]['children'][0]['children'][2]['text'])->toBe('Dolor');
    expect($headings[0]['children'][0]['children'][0]['children'][2]['slug'])->toBe('dolor');

    expect($headings[0]['children'][0]['children'][0]['children'][3]['level'])->toBe(4);
    expect($headings[0]['children'][0]['children'][0]['children'][3]['text'])->toBe('Sit');
    expect($headings[0]['children'][0]['children'][0]['children'][3]['slug'])->toBe('sit');

    expect($headings[0]['children'][0]['children'][0]['children'][4]['level'])->toBe(4);
    expect($headings[0]['children'][0]['children'][0]['children'][4]['text'])->toBe('Amet');
    expect($headings[0]['children'][0]['children'][0]['children'][4]['slug'])->toBe('amet');
});

it('extracts headings from titles with links inside', function () {
    $markdown = <<<'MARKDOWN'
# [*Foo*](https://example.com)
MARKDOWN;

    $headings = extract_headings_from_markdown($markdown);

    expect($headings[0]['text'])->toBe('Foo');
});

it('does not extract headings from lines inside fenced code blocks', function () {
    $markdown = <<<'MD'
```bash
# This should not appear
echo "Foo"
```

# Real heading
MD;

    $headings = extract_headings_from_markdown($markdown);

    expect($headings)->toHaveCount(1)
        ->and($headings[0]['text'])->toBe('Real heading');
});

it('injects newsletter before the 2/3rd h2 when multiple h2 exist', function () {
    $html = <<<'HTML'
<p>Intro</p>
<h2>One</h2>
<p>A</p>
<h2>Two</h2>
<p>B</p>
<h2>Three</h2>
<p>C</p>
<h2>Four</h2>
<p>D</p>
<h2>Five</h2>
<p>E</p>
<h2>Six</h2>
<p>End</p>
HTML;

    $result = inject_newsletter_form($html);

    // Should inject before the 4th h2 (ceil(6 * 2/3) = 4)
    $helloPos = strpos($result, '<p>Hello, World!</p>');
    $h2ThreePos = strpos($result, '<h2>Three</h2>');
    $h2FourPos = strpos($result, '<h2>Four</h2>');

    expect($helloPos)->toBeGreaterThan($h2ThreePos)
        ->and($helloPos)->toBeLessThan($h2FourPos);
});

it('appends newsletter at the end when no h2 exists', function () {
    $html = '<p>Only content</p>';

    $result = inject_newsletter_form($html);

    expect($result)->toEndWith('<p>Hello, World!</p>');
});

it('returns empty string when content is empty', function () {
    $result = inject_newsletter_form('');

    expect($result)->toBe('');
});

it('injects before the only h2 when there is exactly one', function () {
    $html = '<p>Intro</p><h2>Solo</h2><p>Outro</p>';

    $result = inject_newsletter_form($html);

    $helloPos = strpos($result, '<p>Hello, World!</p>');
    $h2SoloPos = strpos($result, '<h2>Solo</h2>');

    expect($helloPos)->toBeLessThan($h2SoloPos);
});
