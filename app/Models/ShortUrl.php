<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Defines the ShortUrl implementation.
 */
class ShortUrl extends Model
{
    use HasFactory;

    protected static function booted() : void
    {
        static::creating(function (self $model) : void {
            $model->code ??= Str::random(5);
        });
    }

    public function link() : Attribute
    {
        return Attribute::make(
            fn () => 'https://' . config('app.url_shortener_domain') . '/' . $this->code,
        );
    }
}
