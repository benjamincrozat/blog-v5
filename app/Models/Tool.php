<?php

namespace App\Models;

use App\Enums\ToolPricingModel;
use Database\Factories\ToolFactory;
use Illuminate\Support\Facades\Vite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Represents a Markdown-managed developer tool.
 */
class Tool extends Model
{
    /** @use HasFactory<ToolFactory> */
    use HasFactory;

    protected function casts() : array
    {
        return [
            'pricing_model' => ToolPricingModel::class,
            'has_free_plan' => 'boolean',
            'has_free_trial' => 'boolean',
            'is_open_source' => 'boolean',
            'categories' => 'array',
            'published_at' => 'datetime',
        ];
    }

    #[Scope]
    protected function published(Builder $query) : void
    {
        $query
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function reviewPost() : BelongsTo
    {
        return $this->belongsTo(Post::class, 'review_post_id');
    }

    public function imageUrl() : Attribute
    {
        return Attribute::make(function () {
            if (blank($this->image_path)) {
                return null;
            }

            if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
                return $this->image_path;
            }

            if (str_starts_with($this->image_path, 'resources/')) {
                return Vite::asset($this->image_path);
            }

            return Storage::disk('cloudflare-images')->url($this->image_path);
        })->shouldCache();
    }

    public function pricingLabel() : Attribute
    {
        return Attribute::make(
            fn () => $this->pricing_model?->label(),
        )->shouldCache();
    }

    public function hasReview() : bool
    {
        return null !== $this->review_post_id;
    }
}
