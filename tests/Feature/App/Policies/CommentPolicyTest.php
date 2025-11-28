<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Policies\CommentPolicy;

it('allows admins via before hook', function () {
    $admin = User::factory()->make([
        'github_login' => 'benjamincrozat',
    ]);

    expect((new CommentPolicy)->before($admin))->toBeTrue();
});

it('permits comment creation and allows authors to delete their comment', function () {
    $user = User::factory()->create([
        'github_login' => 'regular-user',
    ]);

    $comment = Comment::factory()
        ->for($user)
        ->for(Post::factory(), 'post')
        ->create();

    $policy = new CommentPolicy;

    expect($policy->before($user))->toBeNull();
    expect($policy->create($user))->toBeTrue();
    expect($policy->delete($user, $comment))->toBeTrue();
});

it('prevents other users from deleting the comment', function () {
    $author = User::factory()->create([
        'github_login' => 'author-user',
    ]);

    $otherUser = User::factory()->create([
        'github_login' => 'another-user',
    ]);

    $comment = Comment::factory()
        ->for($author)
        ->for(Post::factory(), 'post')
        ->create();

    expect((new CommentPolicy)->delete($otherUser, $comment))->toBeFalse();
});
