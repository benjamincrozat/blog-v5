<?php

namespace App\Livewire;

use App\Models\Link;
use App\Models\Post;
use Livewire\Component;
use Illuminate\View\View;

/**
 * Renders the search modal results for posts and links.
 *
 * Extracted to keep search UI logic centralized in a Livewire component.
 * Callers can rely on non-string query payloads being treated as empty input.
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
