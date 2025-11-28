<?php

use App\Models\Post;
use App\Models\Report;
use App\Models\Revision;

use function Pest\Laravel\assertDatabaseCount;

it('deletes revisions before deleting a report', function () {
    $report = Report::factory()->for(Post::factory())->create();

    Revision::factory(3)
        ->for($report)
        ->create();

    assertDatabaseCount(Revision::class, 3);

    $report->delete();

    assertDatabaseCount(Revision::class, 0);
});

it('provides a descriptive title and casts completion dates', function () {
    $post = Post::factory()->create([
        'title' => 'State of Laravel',
    ]);

    $report = Report::factory()
        ->for($post)
        ->create([
            'completed_at' => now(),
        ]);

    expect($report->title)->toBe('Report #' . $report->id . ' for "State of Laravel"');
    expect($report->completed_at)->not->toBeNull();
    expect($report->post->is($post))->toBeTrue();
});
