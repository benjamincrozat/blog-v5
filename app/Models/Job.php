<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use App\Models\Traits\JobSlugable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
            'perks' => 'array',
            'interview_process' => 'array',
            'equity' => 'boolean',
        ];
    }

    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function locations() : BelongsToMany
    {
        return $this->belongsToMany(
            Location::class,
            'job_listing_location',
            'job_listing_id',
            'location_id'
        );
    }
}
