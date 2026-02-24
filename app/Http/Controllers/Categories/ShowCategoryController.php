<?php

namespace App\Http\Controllers\Categories;

use App\Models\Category;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Actions\BuildBreadcrumbSchema;

/**
 * Displays a category page with its posts.
 *
 * Extracted as a single-action controller to keep routing thin and explicit.
 * Callers can rely on the category being resolved from the route binding.
 */
class ShowCategoryController extends Controller
{
    public function __invoke(Category $category) : View
    {
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Categories', 'url' => route('categories.index')],
            ['label' => $category->name, 'url' => route('categories.show', $category)],
        ];

        return view('categories.show', compact('category') + [
            'posts' => $category
                ->posts()
                ->sponsored()
                ->latest('published_at')
                ->published()
                ->paginate(24),
            'breadcrumbs' => $breadcrumbs,
            'breadcrumbSchema' => app(BuildBreadcrumbSchema::class)->handle($breadcrumbs),
        ]);
    }
}
