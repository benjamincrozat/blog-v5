<?php

namespace App\Http\Controllers\Jobs;

use App\Models\Job;
use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Markdown\MarkdownRenderer;
use App\Http\Controllers\Controller;
use App\Support\Schema\JobPostingSchema;

/**
 * Displays a single job page.
 *
 * Extracted as a single-action controller to keep routing thin and explicit.
 * Callers can rely on the job being resolved from the route binding.
 */
class ShowJobController extends Controller
{
    public function __invoke(Job $job) : View
    {
        $technologies = $job->technologies ?? [];
        sort($technologies, SORT_NATURAL | SORT_FLAG_CASE);

        return view('jobs.show', [
            'job' => $job,
            'jobPostingSchema' => JobPostingSchema::fromJob($job),
            'description' => Str::limit(
                strip_tags(MarkdownRenderer::parse($job->description)),
                160
            ),
            'locations' => $job->locations,
            'technologies' => $technologies,
        ]);
    }
}
