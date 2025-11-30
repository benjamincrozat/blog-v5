<?php

namespace App\Http\Controllers\Jobs;

use App\Models\Job;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Support\Schema\JobPostingSchema;

class ShowJobController extends Controller
{
    public function __invoke(Job $job) : View
    {
        return view('jobs.show', [
            'job' => $job,
            'jobPostingSchema' => JobPostingSchema::fromJob($job),
        ]);
    }
}
