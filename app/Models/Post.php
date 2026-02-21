<?php

namespace App\Models;

use Spatie\Feed\Feedable;
use App\Markdown\Markdown;
use App\Models\Traits\PostFeedable;
use App\Models\Traits\PostSlugable;
use Database\Factories\PostFactory;
use App\Models\Traits\PostSearchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Traits\PostTransformable;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\PostHasTableOfContents;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Defines the Post implementation.
 */
class Post extends Model implements Feedable
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, PostFeedable, PostHasTableOfContents, PostSearchable, PostSlugable, PostTransformable, SoftDeletes;

    protected $withCount = ['comments'];

    protected function casts() : array
    {
        return [
            'sponsored_at' => 'datetime',
            'published_at' => 'datetime',
            'modified_at' => 'datetime',
            'recommendations' => 'collection',
        ];
    }

    #[Scope]
    protected function published(Builder $query) : void
    {
        $query
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    #[Scope]
    protected function unpublished(Builder $query) : void
    {
        $query
            ->whereNull('published_at')
            ->orWhere('published_at', '>', now());
    }

    #[Scope]
    protected function sponsored(Builder $query) : void
    {
        $query
            // Boost posts with recent sponsorship (within a week).
            ->orderByRaw('(sponsored_at IS NOT NULL AND sponsored_at >= ?) DESC', [now()->subWeek()])
            // Within boosted group, order by most recently sponsored.
            ->orderByRaw('CASE WHEN sponsored_at >= ? THEN sponsored_at ELSE NULL END DESC', [now()->subWeek()]);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories() : BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function reports() : HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function link() : HasOne
    {
        return $this->hasOne(Link::class);
    }

    public function formattedContent() : Attribute
    {
        return Attribute::make(
            fn () => Markdown::parse($this->content),
        )->shouldCache();
    }

    public function imageUrl() : Attribute
    {
        return Attribute::make(
            fn () => $this->hasImage() ? Storage::disk($this->image_disk)->url($this->image_path) : null,
        )->shouldCache();
    }

    public function readTime() : Attribute
    {
        return Attribute::make(
            fn () => ceil(str_word_count($this->content) / 200),
        )->shouldCache();
    }

    public function recommendedPosts() : Attribute
    {
        return Attribute::make(
            fn () => empty($this->recommendations) ? null : Post::query()
                ->whereIn('id', $this->recommendations->pluck('id'))
                ->get()
                ->map(function (self $post) {
                    $recommendation = collect($this->recommendations)
                        ->firstWhere('id', $post->id);

                    if (! empty($recommendation['reason'])) {
                        $post->reason = $recommendation['reason'];
                    }

                    return $post;
                }),
        )->shouldCache();
    }

    public function hasImage() : bool
    {
        return $this->image_path && $this->image_disk;
    }

    public function isPublished() : bool
    {
        return ! is_null($this->published_at) && $this->published_at <= now();
    }

    public function isSponsored() : bool
    {
        return ! is_null($this->sponsored_at);
    }

    public function getRouteKeyName() : string
    {
        return 'slug';
    }
}
