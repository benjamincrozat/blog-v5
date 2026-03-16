<?php

namespace App\Models\Traits;

use App\Models\Link;
use Spatie\Feed\FeedItem;
use App\Markdown\MarkdownRenderer;
use Illuminate\Support\Collection;

/**
 * @mixin Link
 */
trait LinkFeedable
{
    public static function getFeedItems() : Collection
    {
        return static::query()
            ->approved()
            ->latest('is_approved')
            ->limit(50)
            ->get();
    }

    public function toFeedItem() : FeedItem
    {
        return FeedItem::create()
            ->id(route('links.index') . '#link-' . $this->getKey())
            ->title($this->title)
            ->summary(MarkdownRenderer::parse($this->description ?? ''))
            ->updated($this->is_approved)
            ->link($this->url)
            ->authorName($this->user->name);
    }
}
