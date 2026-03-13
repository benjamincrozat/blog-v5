<?php

use App\Markdown\Lightdown;
use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Node\Block\Paragraph;
use Tests\Feature\App\Markdown\TestableLightdown;
use League\CommonMark\Extension\CommonMark\Node\Inline\Emphasis;

it('parses markdown, strips disallowed tags, and opens external links in new tabs', function () {
    config(['app.url' => 'https://benjamincrozat.com']);

    $html = Lightdown::parse("[Link](https://example.com)\n<script>alert('x')</script>");

    expect($html)->toContain('target="_blank"');
    expect($html)->toContain('rel="noopener noreferrer"');
    expect($html)->not->toContain('<script>');
});

it('recursively extracts text from CommonMark nodes', function () {
    $paragraph = new Paragraph;
    $paragraph->appendChild(new Text('Hello '));

    $emphasis = new Emphasis;
    $emphasis->appendChild(new Text('world'));
    $paragraph->appendChild($emphasis);

    expect(TestableLightdown::textFromChildren($paragraph))->toBe('Hello world');
});
