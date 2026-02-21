<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Defines the Report implementation.
 */
class Report extends Model
{
    /** @use HasFactory<\Database\Factories\ReportFactory> */
    use HasFactory;

    protected $with = ['post'];

    protected static function booted() : void
    {
        static::deleting(function (self $report) : void {
            // Ensure revisions are deleted before the report.
            $report->revisions()->delete();
        });
    }

    protected function casts() : array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }

    public function post() : BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function revisions() : HasMany
    {
        return $this->hasMany(Revision::class);
    }

    public function title() : Attribute
    {
        return Attribute::make(
            fn () => "Report #$this->id for \"{$this->post->title}\"",
        );
    }
}
