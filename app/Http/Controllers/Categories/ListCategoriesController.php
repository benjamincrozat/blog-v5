<?php

namespace App\Http\Controllers\Categories;

use App\Models\Category;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

/**
 * Defines the ListCategoriesController implementation.
 */
class ListCategoriesController extends Controller
{
    public function __invoke() : View
    {
        return view('categories.index', [
            'categories' => Category::query()
                ->withCount('posts')
                ->orderBy('name')
                ->get(),
        ]);
    }
}
