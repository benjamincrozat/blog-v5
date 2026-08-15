<?php

namespace App\Http\Controllers\Categories;

use App\Models\Category;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Actions\BuildBreadcrumbSchema;

/**
 * Builds one category page from its published posts.
 *
 * Recently sponsored posts come first, followed by the newest publication date,
 * and results are split into pages of 24. The visible breadcrumbs and JSON-LD
 * use the same data. Laravel's route lookup returns 404 for an unknown category.
 */
class ShowCategoryController extends Controller
{
    public function __invoke(Category $category) : View
    {
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Categories', 'url' => route('categories.index')],
            ['label' => $category->name],
        ];

        $posts = $category->posts()
            ->published()
            ->sponsored()
            ->latest('published_at');

        return view('categories.show', compact('category') + [
            'posts' => $posts->paginate(24),
            'breadcrumbs' => $breadcrumbs,
            'breadcrumbSchema' => app(BuildBreadcrumbSchema::class)->handle($breadcrumbs),
        ]);
    }
}
