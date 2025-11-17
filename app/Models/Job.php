<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use App\Models\Traits\JobSlugable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    /** @use HasFactory<\Database\Factories\JobFactory> */
    use HasFactory, JobSlugable, Searchable;

    // Avoid conflict with the already existing jobs table.
    protected $table = 'job_listings';

    // Slug handling is implemented via JobSlugable.

    protected function casts() : array
    {
        return [
            'technologies' => 'array',
            'locations' => 'array',
            'perks' => 'array',
            'interview_process' => 'array',
            'equity' => 'boolean',
        ];
    }

    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
