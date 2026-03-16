<?php

namespace App\Http\Controllers\Tools;

use App\Models\Tool;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Actions\BuildBreadcrumbSchema;

/**
 * Shows the public tools page with breadcrumb schema data.
 */
class ListToolsController extends Controller
{
    public function __invoke() : View
    {
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Tools'],
        ];

        return view('tools.index', [
            'tools' => Tool::query()
                ->with('reviewPost')
                ->published()
                ->orderByDesc('published_at')
                ->orderBy('name')
                ->get(),
            'breadcrumbs' => $breadcrumbs,
            'breadcrumbSchema' => app(BuildBreadcrumbSchema::class)->handle($breadcrumbs),
        ]);
    }
}
