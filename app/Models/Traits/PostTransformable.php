<?php

namespace App\Models\Traits;

use App\Models\Post;
use App\Markdown\PostMarkdownDocument;

/**
 * @mixin Post
 */
trait PostTransformable
{
    public function toMarkdown() : string
    {
        return PostMarkdownDocument::fromPost($this)->toMarkdown();
    }
}
