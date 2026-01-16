<?php

use App\Feed\FeedReader;
use Carbon\CarbonImmutable;

it('parses atom feeds, strips trackers, and resolves relative urls', function () {
    $atom = <<<'XML'
        <?xml version="1.0" encoding="utf-8"?>
        <feed xmlns="http://www.w3.org/2005/Atom">
            <entry>
                <title>Tom &amp; Jerry</title>
                <link rel="alternate" type="text/html" href="/post-one?utm_source=newsletter#comments"/>
                <published>2024-09-01T15:04:05Z</published>
            </entry>
            <entry>
                <title>Second post</title>
                <link href="https://others.example.com/posts/second?utm_campaign=feed" />
                <updated>Wed, 04 Sep 2024 10:15:00 GMT</updated>
            </entry>
        </feed>
        XML;

    $items = (new FeedReader)->read($atom, 'https://example.com/feed/index.xml');

    expect($items)->toHaveCount(2);
    expect($items[0]->url)->toBe('https://example.com/post-one');
    expect($items[0]->title)->toBe('Tom & Jerry');
    expect($items[0]->publishedAt)->toEqual(new CarbonImmutable('2024-09-01T15:04:05Z'));

    expect($items[1]->url)->toBe('https://others.example.com/posts/second');
    expect($items[1]->publishedAt)->toEqual(CarbonImmutable::parse('Wed, 04 Sep 2024 10:15:00 GMT'));
});

it('falls back to RSS parsing and extracts links from guid or content', function () {
    $rss = <<<'XML'
        <?xml version="1.0" encoding="UTF-8" ?>
        <rss version="2.0"
             xmlns:content="http://purl.org/rss/1.0/modules/content/"
             xmlns:dc="http://purl.org/dc/elements/1.1/">
            <channel>
                <title>Example RSS</title>
                <item>
                    <title>Guid fallback</title>
                    <link></link>
                    <guid>https://example.com/guid-post?utm_medium=email#section</guid>
                    <pubDate>Wed, 18 Sep 2024 12:00:00 GMT</pubDate>
                </item>
                <item>
                    <title>Content link</title>
                    <link></link>
                    <guid isPermaLink="false"></guid>
                    <content:encoded><![CDATA[
                        <p>Check <a href="/articles/second?utm_campaign=feed#comments">this</a>.</p>
                    ]]></content:encoded>
                    <dc:date>invalid-date-format</dc:date>
                </item>
            </channel>
        </rss>
        XML;

    $items = (new FeedReader)->read($rss, 'https://example.com/feed/index.xml');

    expect($items)->toHaveCount(2);

    expect($items[0]->url)->toBe('https://example.com/guid-post');
    expect($items[0]->publishedAt)->toEqual(CarbonImmutable::parse('Wed, 18 Sep 2024 12:00:00 GMT'));

    expect($items[1]->url)->toBe('https://example.com/articles/second');
    expect($items[1]->publishedAt)->toBeNull();
});

it('normalizes tricky urls via the helper', function () {
    $reader = new FeedReader;

    $normalize = Closure::bind(
        fn (string $url) => $this->normalizeUrl($url, 'https://example.com/feeds/blog/index.xml'),
        $reader,
        FeedReader::class,
    );

    expect($normalize('http://'))->toBeNull();
    expect($normalize('//cdn.example.com/assets/script.js#frag'))->toBe('https://cdn.example.com/assets/script.js');
    expect($normalize('changelog/launch'))->toBe('https://example.com/feeds/blog/changelog/launch');
});

it('returns an empty list for invalid xml', function () {
    $items = (new FeedReader)->read('<not-xml', 'https://example.com/feed/index.xml');

    expect($items)->toBeArray()
        ->and($items)->toHaveCount(0);
});
