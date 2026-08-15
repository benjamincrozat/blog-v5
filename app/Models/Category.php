<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Groups blog posts under one public topic.
 *
 * The posts relationship contains the full category used by archives and the
 * Markdown sync. The activity relationship gives navigation a smaller list: the
 * five published posts with the most recorded visits.
 */
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

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
