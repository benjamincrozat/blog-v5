<?php

use App\Models\Job;
use App\Models\Link;
use App\Models\Post;
use App\Models\User;
use App\Models\Report;
use App\Jobs\ReviseJob;
use App\Jobs\ReviewPost;
use App\Jobs\RevisePost;
use App\Scraper\Webpage;
use App\Jobs\RecommendPosts;
use App\Jobs\RefreshUserData;
use App\Jobs\CreatePostForLink;
use App\Jobs\FetchJobData as FetchJobDataJob;
use Facades\App\Actions\ReviseJob as ReviseJobAction;
use Facades\App\Actions\ReviewPost as ReviewPostAction;
use Facades\App\Actions\RevisePost as RevisePostAction;
use Facades\App\Actions\FetchJobData as FetchJobDataAction;
use Facades\App\Actions\RecommendPosts as RecommendPostsAction;
use Facades\App\Actions\RefreshUserData as RefreshUserDataAction;
use Facades\App\Actions\CreatePostForLink as CreatePostForLinkAction;

it('delegates the create post for link job to its action', function () {
    $link = Link::factory()->make();

    CreatePostForLinkAction::shouldReceive('create')
        ->once()
        ->with($link);

    (new CreatePostForLink($link))->handle();
});

it('delegates the fetch job data job to its action', function () {
    $webpage = new Webpage('https://example.com', null, 'Title', '<p>Content</p>');

    FetchJobDataAction::shouldReceive('fetch')
        ->once()
        ->with($webpage);

    (new FetchJobDataJob($webpage))->handle();
});

it('delegates the review job with additional instructions', function () {
    $post = Post::factory()->make();

    ReviewPostAction::shouldReceive('review')
        ->once()
        ->with($post, 'Add a TL;DR');

    (new ReviewPost($post, 'Add a TL;DR'))->handle();
});

it('delegates the revise job with optional instructions', function () {
    $job = Job::factory()->make();

    ReviseJobAction::shouldReceive('revise')
        ->once()
        ->with($job, null);

    (new ReviseJob($job))->handle();
});

it('delegates the revise post job with report and instructions', function () {
    $post = Post::factory()->make();
    $report = Report::factory()->make();

    RevisePostAction::shouldReceive('revise')
        ->once()
        ->with($post, $report, 'Tighten copy');

    (new RevisePost($post, $report, 'Tighten copy'))->handle();
});

it('delegates recommending posts for a post', function () {
    $post = Post::factory()->make();

    RecommendPostsAction::shouldReceive('recommend')
        ->once()
        ->with($post);

    (new RecommendPosts($post))->handle();
});

it('delegates refreshing user data', function () {
    $user = User::factory()->make();

    RefreshUserDataAction::shouldReceive('refresh')
        ->once()
        ->with($user);

    (new RefreshUserData($user))->handle();
});
