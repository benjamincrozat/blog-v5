<?php

namespace App\Http\Controllers\Categories;

use App\Models\Category;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Actions\BuildBreadcrumbSchema;

/**
 * Builds the public list of blog categories.
 *
 * It sorts categories by name and includes each category's current post count.
 * The same breadcrumb list is used for the visible page and its JSON-LD data.
 * Published-post filtering happens on the individual category page, not here.
 */
class ListCategoriesController extends Controller
{
    public function __invoke() : View
    {
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Categories'],
        ];

        return view('categories.index', [
            'categories' => Category::query()
                ->withCount('posts')
                ->orderBy('name')
                ->get(),
            'breadcrumbs' => $breadcrumbs,
            'breadcrumbSchema' => app(BuildBreadcrumbSchema::class)->handle($breadcrumbs),
        ]);
    }
}
