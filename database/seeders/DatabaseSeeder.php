<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Console\Commands\SyncVisitorsCommand;

class DatabaseSeeder extends Seeder
{
    public function run() : void
    {
        Storage::disk('public')->deleteDirectory('images/posts');

        cache()->flush();

        Artisan::call(SyncVisitorsCommand::class);

        $this->call([
            UserSeeder::class,
            PostSeeder::class,
            CommentSeeder::class,
            LinkSeeder::class,
            ShortUrlSeeder::class,
            QuizSeeder::class,
        ]);
    }
}
