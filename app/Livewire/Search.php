<?php

namespace App\Livewire;

use App\Models\Link;
use App\Models\Post;
use Livewire\Component;
use Illuminate\View\View;

/**
 * Searches the post and community-link indexes for the site search window.
 *
 * Blank input and invalid non-text browser data skip both searches. A real query
 * returns up to five posts and five links. The model rules keep unpublished
 * posts and unapproved links out of these public search indexes.
 */
class Search extends Component
{
    public string|array $query = '';

    public function render() : View
    {
        $this->query = $this->normalizedQuery();

        $query = $this->query;

        return view('livewire.search', [
            'query' => $query,
            'posts' => '' === $query
                ? collect()
                : Post::search($query)
                    ->take(5)
                    ->get(),
            'links' => '' === $query
                ? collect()
                : Link::search($query)
                    ->take(5)
                    ->get(),
        ]);
    }

    protected function normalizedQuery() : string
    {
        return is_string($this->query) ? $this->query : '';
    }
}
