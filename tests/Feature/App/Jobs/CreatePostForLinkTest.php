<?php

use App\Models\Link;
use App\Jobs\CreatePostForLink;
use Facades\App\Actions\CreatePostForLink as CreatePostForLinkAction;

it('delegates the create post for link job to its action', function () {
    $link = Link::factory()->make();

    CreatePostForLinkAction::shouldReceive('create')
        ->once()
        ->with($link);

    (new CreatePostForLink($link))->handle();
});
