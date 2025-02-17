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
