<?php

namespace App\Http\Controllers\Categories;

use App\Models\Post;
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
        $isNewsCategory = Post::NEWS_CATEGORY_SLUG === $category->slug;
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Categories', 'url' => route('categories.index')],
            ['label' => $category->name],
        ];

        $posts = $category->posts()->published();

        if ($isNewsCategory) {
            $posts->latest('published_at');
        } else {
            $posts
                ->sponsored()
                ->latest('published_at');
        }

        return view('categories.show', compact('category') + [
            'posts' => $posts->paginate(24),
            'isNewsCategory' => $isNewsCategory,
            'breadcrumbs' => $breadcrumbs,
            'breadcrumbSchema' => app(BuildBreadcrumbSchema::class)->handle($breadcrumbs),
        ]);
    }
}
