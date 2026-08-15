<?php

namespace App\Models\Traits;

use App\Models\Link;
use Laravel\Scout\Searchable;

/**
 * Defines what Scout stores and searches for a community link.
 *
 * The search record contains the submitter, URL, title, and description used by
 * the site search window. Only approved links are added, so pending and declined
 * links cannot appear in public results.
 *
 * @mixin Link
 */
trait LinkSearchable
{
    use Searchable;

    public function toSearchableArray() : array
    {
        return [
            'user_name' => $this->user->name,
            'url' => $this->url,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }

    public function shouldBeSearchable() : bool
    {
        return $this->isApproved();
    }
}
