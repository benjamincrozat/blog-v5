<?php

namespace App\Http\Controllers\Tools;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Actions\BuildBreadcrumbSchema;

/**
 * Coordinates a single-action HTTP endpoint for the site.
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
            'breadcrumbs' => $breadcrumbs,
            'breadcrumbSchema' => app(BuildBreadcrumbSchema::class)->handle($breadcrumbs),
        ]);
    }
}
