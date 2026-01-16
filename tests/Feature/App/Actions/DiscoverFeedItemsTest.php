<?php

use App\Actions\DiscoverFeedItems;
use Illuminate\Support\Facades\Http;

it('extracts url, publishedAt and title from LaraJobs RSS', function () {
    $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:wfw="http://wellformedweb.org/CommentAPI/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
     xmlns:job="https://larajobs.com"
>

    <channel>
        <title>LaraJobs</title>
        <atom:link href="https://larajobs.com" rel="self" type="application/rss+xml" />
        <link>https://larajobs.com</link>
        <description>Your Laravel Jobs source.</description>
        <lastBuildDate>Wed, 01 Oct 2025 01:49:18 +0000</lastBuildDate>
        <language>en-US</language>
        <sy:updatePeriod>hourly</sy:updatePeriod>
        <sy:updateFrequency>1</sy:updateFrequency>
        <generator>https://larajobs.com</generator>
                    <item>
                <title>Laravel Software Engineer</title>
                <link>https://larajobs.com/job/3720</link>
                <pubDate>Wed, 01 Oct 2025 01:49:18 +0000</pubDate>
                <dc:creator><![CDATA[Amalfi Jets]]></dc:creator>
                <category><![CDATA[Job]]></category>
                <job:location><![CDATA[Calabasas, CA, USA 91302]]></job:location>
                <job:job_type><![CDATA[FULL_TIME]]></job:job_type>
                <job:salary><![CDATA[$110,000 - $140,000 USD]]></job:salary>
                <job:company><![CDATA[Amalfi Jets]]></job:company>
                <job:company_logo>https://larajobs.com/logos/KhBmbUq26lcrU8sH7Xc15jMdQKg7IA5F9WKngEnA.png</job:company_logo>
                <job:tags><![CDATA[Backend,Laravel,PHP,React,TailwindCSS]]></job:tags>
                <guid isPermaLink="false">https://larajobs.com/job/3720</guid>
                <description><![CDATA[

                    ]]></description>
                <content:encoded><![CDATA[

                    ]]></content:encoded>
            </item>
    </channel>
</rss>
XML;

    Http::fake([
        'https://larajobs.com/feed' => Http::response($xml, 200, ['Content-Type' => 'application/rss+xml']),
    ]);

    $action = new DiscoverFeedItems;

    $items = $action->discover('https://larajobs.com/feed');

    expect($items)->toHaveCount(1);
    $item = $items->first();
    expect($item->url)->toBe('https://larajobs.com/job/3720');
    expect($item->title)->toBe('Laravel Software Engineer');
    expect($item->publishedAt)->not->toBeNull();
});

it('extracts from Atom with rel="alternate" and published/updated', function () {
    $xml = <<<'XML'
<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title>Example Feed</title>
  <link href="https://example.com/feed" rel="self" />
  <updated>2025-10-01T01:49:18Z</updated>
  <entry>
    <title>Item A</title>
    <link href="/posts/a" rel="alternate" type="text/html" />
    <id>tag:example.com,2025:/posts/a</id>
    <published>2025-10-01T00:00:00Z</published>
  </entry>
  <entry>
    <title>Item B</title>
    <link href="/posts/b" />
    <id>https://example.com/posts/b</id>
    <updated>2025-10-01T02:00:00Z</updated>
  </entry>
  <entry>
    <title></title>
    <link href="/no-title" rel="alternate" />
  </entry>
  <entry>
    <title>No link</title>
  </entry>
</feed>
XML;

    Http::fake([
        'https://example.com/feed' => Http::response($xml, 200, ['Content-Type' => 'application/atom+xml']),
    ]);

    $action = new DiscoverFeedItems;

    $items = $action->discover('https://example.com/feed');

    expect($items)->toHaveCount(2);
    expect($items[0]->url)->toBe('https://example.com/posts/a');
    expect($items[0]->title)->toBe('Item A');
    expect($items[0]->publishedAt)->not->toBeNull();
    expect($items[1]->url)->toBe('https://example.com/posts/b');
    expect($items[1]->title)->toBe('Item B');
    expect($items[1]->publishedAt)->not->toBeNull();
});

it('falls back to GUID or first anchor in content:encoded for RSS', function () {
    $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">
  <channel>
    <title>Fallbacks</title>
    <item>
      <title>Guid Link</title>
      <guid>https://example.com/guid-only</guid>
      <pubDate>Wed, 01 Oct 2025 01:49:18 +0000</pubDate>
    </item>
    <item>
      <title>Encoded Anchor</title>
      <content:encoded><![CDATA[<p>See <a href="https://example.com/encoded">here</a></p>]]></content:encoded>
      <pubDate>Wed, 01 Oct 2025 01:49:18 +0000</pubDate>
    </item>
  </channel>
</rss>
XML;

    Http::fake([
        'https://example.com/feed' => Http::response($xml, 200, ['Content-Type' => 'application/rss+xml']),
    ]);

    $action = new DiscoverFeedItems;

    $items = $action->discover('https://example.com/feed');

    expect($items)->toHaveCount(2);
    expect($items[0]->url)->toBe('https://example.com/guid-only');
    expect($items[1]->url)->toBe('https://example.com/encoded');
});

it('returns empty collection on non-2xx responses', function () {
    Http::fake([
        'https://example.com/feed' => Http::response('error', 500, ['Content-Type' => 'text/plain']),
    ]);

    $action = new DiscoverFeedItems;

    $items = $action->discover('https://example.com/feed');

    expect($items)->toHaveCount(0);
});

it('uses the self link to resolve relative urls', function () {
    $xml = <<<'XML'
<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <link href="https://alt.example.com/feed/index.xml" rel="self" />
  <entry>
    <title>Item A</title>
    <link href="/posts/a" rel="alternate" type="text/html" />
    <published>2025-10-01T00:00:00Z</published>
  </entry>
</feed>
XML;

    Http::fake([
        'https://example.com/feed' => Http::response($xml, 200, ['Content-Type' => 'application/atom+xml']),
    ]);

    $items = (new DiscoverFeedItems)->discover('https://example.com/feed');

    expect($items)->toHaveCount(1);
    expect($items[0]->url)->toBe('https://alt.example.com/posts/a');
});
