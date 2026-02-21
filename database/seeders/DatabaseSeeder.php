<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Console\Commands\SyncVisitorsCommand;

/**
 * Defines the DatabaseSeeder implementation.
 */
class DatabaseSeeder extends Seeder
{
    public function run() : void
    {
        Storage::disk('public')->deleteDirectory('images/posts');

        cache()->flush();

        Artisan::call(SyncVisitorsCommand::class);

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            PostSeeder::class,
            CommentSeeder::class,
            ReportSeeder::class,
            LinkSeeder::class,
            CompanySeeder::class,
            JobSeeder::class,
            ShortUrlSeeder::class,
        ]);
    }
}
