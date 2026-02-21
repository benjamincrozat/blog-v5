<?php

namespace App\Models;

use App\Notifications\LinkApproved;
use Database\Factories\LinkFactory;
use App\Models\Traits\LinkSearchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Defines the Link implementation.
 */
class Link extends Model
{
    /** @use HasFactory<LinkFactory> */
    use HasFactory, LinkSearchable;

    protected function casts() : array
    {
        return [
            'is_approved' => 'datetime',
            'is_declined' => 'datetime',
        ];
    }

    #[Scope]
    public function pending(Builder $query) : void
    {
        $query
            ->whereNull('is_declined')
            ->whereNull('is_approved');
    }

    #[Scope]
    public function approved(Builder $query) : void
    {
        $query
            ->whereNotNull('is_approved')
            ->whereNull('is_declined');
    }

    #[Scope]
    public function declined(Builder $query) : void
    {
        $query
            ->whereNotNull('is_declined')
            ->whereNull('is_approved');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post() : BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function domain() : Attribute
    {
        return Attribute::make(
            fn () => str_replace('www.', '', parse_url($this->url, PHP_URL_HOST)),
        );
    }

    public function approve(?string $notes = null) : self
    {
        $this->update([
            'notes' => $notes,
            'is_approved' => now(),
            'is_declined' => null,
        ]);

        $this->user->notify(new LinkApproved($this));

        return $this;
    }

    public function decline() : self
    {
        $this->update([
            'is_declined' => now(),
            'is_approved' => null,
        ]);

        return $this;
    }

    public function isApproved() : bool
    {
        return null !== $this->is_approved && null === $this->is_declined;
    }

    public function isDeclined() : bool
    {
        return null !== $this->is_declined && null === $this->is_approved;
    }
}
