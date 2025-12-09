<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Actions\NormalizeCompanyUrl;
use App\Actions\NormalizeCompanyDomain;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;

    protected static function booted() : void
    {
        static::creating(function (self $company) {
            $company->slug = Str::slug($company->name);
        });

        static::saving(function (self $company) {
            $normalizedUrl = app(NormalizeCompanyUrl::class)
                ->handle($company->url);

            $company->url = $normalizedUrl;
            $company->domain = app(NormalizeCompanyDomain::class)
                ->handle($normalizedUrl ?? $company->url);
        });
    }
}
