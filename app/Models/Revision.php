<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Defines the Revision implementation.
 */
class Revision extends Model
{
    /** @use HasFactory<\Database\Factories\RevisionFactory> */
    use HasFactory;

    protected function casts() : array
    {
        return [
            'data' => 'array',
            'completed_at' => 'datetime',
        ];
    }

    public function report() : BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function title() : Attribute
    {
        return Attribute::make(
            fn () => "Revision for \"{$this->report->post->title}\"",
        );
    }
}
