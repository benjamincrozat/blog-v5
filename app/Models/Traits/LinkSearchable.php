<?php

namespace App\Models\Traits;

use App\Models\Link;
use Laravel\Scout\Searchable;

/**
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
