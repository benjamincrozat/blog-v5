<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Stores a permanent move from an old post slug to its current slug.
 *
 * PostSlugable creates these rows and shortens old redirect chains when a slug
 * changes. HandleRedirects reads them before normal requests continue. This model
 * only stores the two slugs; the trait changes them and the middleware sends the 301.
 */
class Redirect extends Model
{
    use HasFactory;
}
