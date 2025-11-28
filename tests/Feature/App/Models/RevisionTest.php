<?php

use App\Models\Post;
use App\Models\Report;
use App\Models\Revision;

it('casts attributes and exposes relations for revisions', function () {
    $post = Post::factory()->create([
        'title' => 'LLM Benchmarks',
    ]);

    $report = Report::factory()->for($post)->create();

    $revision = Revision::factory()
        ->for($report)
        ->create([
            'data' => ['notes' => 'Add more sources'],
            'completed_at' => now(),
        ]);

    expect($revision->report->is($report))->toBeTrue();
    expect($revision->data)->toBe(['notes' => 'Add more sources']);
    expect($revision->completed_at)->not->toBeNull();
    expect($revision->title)->toBe('Revision for "LLM Benchmarks"');
});
