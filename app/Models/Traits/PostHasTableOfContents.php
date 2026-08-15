<?php

namespace App\Models\Traits;

use App\Markdown\TableOfContents;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;

/**
 * Adds a rendered table of contents to a model with Markdown content.
 *
 * It turns the model's headings into a tree, renders the shared Blade component,
 * and returns HTML that article views can print safely. TableOfContents reads the
 * headings, while the Blade component owns the final markup.
 *
 * @mixin Model
 */
trait PostHasTableOfContents
{
    public function toTableOfContents() : HtmlString
    {
        return new HtmlString(
            view('components.table-of-contents.index', [
                'items' => new TableOfContents($this->content)->toArray(),
            ])->render()
        );
    }
}
