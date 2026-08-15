<?php

namespace App\Models\Traits;

use App\Models\Post;
use Spatie\Feed\FeedItem;
use App\Markdown\MarkdownRenderer;
use Illuminate\Support\Collection;

/**
 * Adds standalone blog posts to the Atom feed.
 *
 * It selects the 50 newest published posts that are not community-link items.
 * Each feed item points to the article and uses its latest edit or publication
 * date. It also includes a short description with a link back to the site.
 *
 * @mixin Post
 */
trait PostFeedable
{
    public static function getFeedItems() : Collection
    {
        return static::query()
            ->published()
            ->whereDoesntHave('link')
            ->latest('published_at')
            ->limit(50)
            ->get();
    }

    public function toFeedItem() : FeedItem
    {
        $link = route('posts.show', $this);

        return FeedItem::create()
            ->id($this->slug)
            ->title($this->title)
            ->summary(MarkdownRenderer::parse($this->description . <<<MARKDOWN

[Read more →]($link)

If you like my feed, follow me on [X](https://x.com/benjamincrozat), [LinkedIn](https://www.linkedin.com/in/benjamincrozat/), and [GitHub](https://github.com/benjamincrozat).
MARKDOWN ?? ''))
            ->updated($this->modified_at ?? $this->published_at)
            ->link($link)
            ->authorName($this->user->name);
    }
}
