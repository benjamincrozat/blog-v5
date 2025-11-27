<?php

namespace App\Http\Controllers\Authors;

use App\Models\User;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class ShowAuthorController extends Controller
{
    public function __invoke(string $slug) : View
    {
        $user = User::query()
            ->where('slug', $slug)
            ->where(function (Builder $query) {
                $query
                    ->whereHas('posts')
                    ->orWhereHas('links', fn (Builder $links) => $links->approved());
            })
            ->firstOrFail();

        return view('authors.show', [
            'author' => $user,

            'posts' => $user->posts()
                ->latest('published_at')
                ->published()
                ->paginate(12),

            'links' => $user->links()
                ->latest('is_approved')
                ->approved()
                ->paginate(12),
        ]);
    }
}
