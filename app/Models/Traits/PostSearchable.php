<?php

namespace App\Models\Traits;

use App\Models\Post;
use Laravel\Scout\Searchable;

/**
 * Defines what Scout stores and searches for a blog post.
 *
 * The search record contains the article text, author, and category names used by
 * the site search window. Only published posts are added, so drafts and scheduled
 * posts cannot appear in public results.
 *
 * @mixin Post
 */
trait PostSearchable
{
    use Searchable;

    public function toSearchableArray() : array
    {
        return [
            'user_name' => $this->user->name,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'description' => $this->description,
            'categories' => $this->categories->pluck('name')->toArray(),
        ];
    }

    public function shouldBeSearchable() : bool
    {
        return $this->isPublished();
    }
}
