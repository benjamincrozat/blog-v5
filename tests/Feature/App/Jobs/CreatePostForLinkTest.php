<?php

use App\Models\Link;
use App\Jobs\CreatePostForLink;
use Mockery\MockInterface;
use App\Actions\CreatePostForLink as CreatePostForLinkAction;

it('delegates the create post for link job to its action', function () {
    $link = Link::factory()->make();

    $action = mock(CreatePostForLinkAction::class, function (MockInterface $mock) use ($link) {
        $mock->shouldReceive('create')
            ->once()
            ->with($link);
    });

    app()->instance(CreatePostForLinkAction::class, $action);

    (new CreatePostForLink($link))->handle();
});
