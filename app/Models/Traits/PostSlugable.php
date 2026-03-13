<?php

namespace App\Models\Traits;

use App\Models\Post;
use App\Models\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

/**
 * @mixin Post
 */
trait PostSlugable
{
    public static function bootPostSlugable() : void
    {
        static::creating(
            function (Post $post) {
                $post->slug ??= Str::slug($post->title);
            }
        );

        static::updating(function (Post $post) {
            if (! $post->isDirty('slug')) {
                return;
            }

            $old = $post->getOriginal('slug');

            $new = $post->slug;

            if (! filled($old) || ! filled($new) || $old === $new) {
                return;
            }

            DB::transaction(function () use ($old, $new) {
                // 1. Remove any redirect originating from the new slug. It would
                // create a loop once the slug becomes its own destination.
                Redirect::query()->where('from', $new)->delete();

                // 2. Point existing redirects that ended at
                // the old slug directly to the new slug.
                Redirect::query()->where('to', $old)->update(['to' => $new]);

                // 3. Create or update the canonical redirect from the old slug to the new slug.
                Redirect::query()->updateOrCreate(
                    ['from' => $old],
                    ['to' => $new]
                );
            });
        });
    }
}
