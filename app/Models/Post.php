<?php

namespace App\Models;

use Spatie\Feed\Feedable;
use App\Markdown\MarkdownRenderer;
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
 * Represents post records.
 */
class Post extends Model implements Feedable
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, PostFeedable, PostHasTableOfContents, PostSearchable, PostSlugable, PostTransformable, SoftDeletes;

    public const NEWS_CATEGORY_SLUG = 'news';

    protected $withCount = ['comments'];

    protected function casts() : array
    {
        return [
            'is_commercial' => 'boolean',
            'sponsored_at' => 'datetime',
            'published_at' => 'datetime',
            'modified_at' => 'datetime',
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

    #[Scope]
    protected function news(Builder $query) : void
    {
        $query->whereHas('categories', fn (Builder $categories) => $categories->where('slug', static::NEWS_CATEGORY_SLUG));
    }

    #[Scope]
    protected function newsEligible(Builder $query) : void
    {
        $query
            ->published()
            ->news()
            ->where('is_commercial', false)
            ->whereNull('sponsored_at')
            ->whereDoesntHave('link');
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

    public function link() : HasOne
    {
        return $this->hasOne(Link::class);
    }

    public function reviewedTool() : HasOne
    {
        return $this->hasOne(Tool::class, 'review_post_id');
    }

    public function formattedContent() : Attribute
    {
        return Attribute::make(
            fn () => MarkdownRenderer::parse($this->content),
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

    public function isNews() : bool
    {
        if ($this->relationLoaded('categories')) {
            return $this->categories->contains(fn (Category $category) => static::NEWS_CATEGORY_SLUG === $category->slug);
        }

        return $this->categories()->where('slug', static::NEWS_CATEGORY_SLUG)->exists();
    }

    public function isNewsEligible() : bool
    {
        if (! $this->isPublished() || $this->is_commercial || $this->isSponsored() || ! $this->isNews()) {
            return false;
        }

        if ($this->relationLoaded('link')) {
            return is_null($this->link);
        }

        return ! $this->link()->exists();
    }

    public function getRouteKeyName() : string
    {
        return 'slug';
    }
}
