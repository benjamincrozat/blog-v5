<?php

namespace App\Models\Traits;

use App\Models\Job;
use App\Models\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

/**
 * @mixin \App\Models\Job
 */
trait JobSlugable
{
    public static function bootJobSlugable() : void
    {
        static::creating(
            fn (Job $job) => $job->fillRandomizedSlugFromTitle()
        );

        static::updating(function (Job $job) {
            $old = $job->getOriginal('slug');

            if ($job->isDirty('title')) {
                $job->fillRandomizedSlugFromTitle();
            }

            $new = $job->slug;

            if (! filled($old) || ! filled($new) || $old === $new) {
                return;
            }

            $oldPath = 'jobs/' . ltrim($old, '/');
            $newPath = 'jobs/' . ltrim($new, '/');

            DB::transaction(function () use ($oldPath, $newPath) {
                // Avoid circular redirects for job paths.
                Redirect::query()->where('from', $newPath)->delete();

                // Point existing redirects terminating at the old path directly to the new path.
                Redirect::query()->where('to', $oldPath)->update(['to' => $newPath]);

                // Create or update canonical redirect from the old path to the new path.
                Redirect::query()->updateOrCreate(
                    ['from' => $oldPath],
                    ['to' => $newPath]
                );
            });
        });
    }

    protected function fillRandomizedSlugFromTitle() : void
    {
        $this->slug = Str::slug(Str::random(10) . ' ' . $this->title);
    }
}
