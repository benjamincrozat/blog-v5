<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Seeder;

/**
 * Defines the CommentSeeder implementation.
 */
class CommentSeeder extends Seeder
{
    public function run() : void
    {
        Comment::factory(100)
            ->recycle(User::all())
            ->recycle(Post::query()->where('is_commercial', false)->get())
            ->create();
    }
}
