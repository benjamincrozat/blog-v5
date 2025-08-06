<?php

namespace Database\Seeders;

use App\Models\Link;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class LinkSeeder extends Seeder
{
    public function run() : void
    {
        Link::factory(30)
            ->recycle(User::all())
            ->approved()
            ->create();

        Link::factory(10)
            ->recycle(User::all())
            ->recycle(Post::query()->inRandomOrder()->get())
            ->approved()
            ->withPost()
            ->create();
    }
}
