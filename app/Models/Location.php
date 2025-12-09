<?php

namespace App\Models;

use Database\Factories\LocationFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Location extends Model
{
    /** @use HasFactory<LocationFactory> */
    use HasFactory;

    protected $fillable = [
        'city',
        'region',
        'country',
    ];

    public function jobs() : BelongsToMany
    {
        return $this->belongsToMany(
            Job::class,
            'job_listing_location',
            'location_id',
            'job_listing_id'
        );
    }

    public function getDisplayNameAttribute() : string
    {
        $city = trim((string) $this->city);
        $region = trim((string) $this->region);
        $country = trim((string) $this->country);

        $parts = array_filter([$city, $region, $country], static fn (string $value) : bool => '' !== $value);

        return implode(', ', $parts);
    }
}
