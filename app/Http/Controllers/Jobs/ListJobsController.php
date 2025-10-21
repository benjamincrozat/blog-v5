<?php

namespace App\Http\Controllers\Jobs;

use App\Models\Job;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class ListJobsController extends Controller
{
    public function __invoke() : View
    {
        return view('jobs.index', [
            'jobs' => Job::query()
                ->latest()
                ->paginate(12),

            'recentJobsCount' => Job::query()
                ->where('created_at', '>=', now()->subDays(30))
                ->count(),
        ]);
    }
}
