<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Represents category records.
 */
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    protected function casts() : array
    {
        return [
            'modified_at' => 'datetime',
        ];
    }

    public function posts() : BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    public function activity() : BelongsToMany
    {
        return $this
            ->belongsToMany(Post::class)
            ->published()
            ->orderBy('sessions_count', 'desc')
            ->limit(5);
    }
}
