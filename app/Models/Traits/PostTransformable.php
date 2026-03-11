<?php

namespace App\Models\Traits;

use App\Markdown\PostMarkdownDocument;

/**
 * @mixin \App\Models\Post
 */
trait PostTransformable
{
    public function toMarkdown() : string
    {
        return PostMarkdownDocument::fromPost($this)->toMarkdown();
    }
}
