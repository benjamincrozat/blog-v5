<?php

namespace App\Models\Traits;

/**
 * @mixin \App\Models\Post
 */
trait PostTransformable
{
    public function toMarkdown() : string
    {
        // Ensure categories are loaded so we can list them in the front matter.
        $this->loadMissing('categories');

        /** @var array<string, mixed> $frontMatter */
        $frontMatter = collect([
            'slug' => $this->slug,
            'description' => $this->description,
            'canonical_url' => $this->canonical_url,
            'serp_title' => $this->serp_title,
            'published_at' => $this->published_at?->toDateTimeString(),
            'modified_at' => $this->modified_at?->toDateTimeString(),
            'categories' => $this->categories->pluck('name')->implode(', '),
        ])->filter()->toArray();

        // Build the YAML-like front matter block.
        $frontMatterLines = collect($frontMatter)
            ->map(fn ($value, string $key) => "$key: $value")
            ->implode("\n");

        return "---\n{$frontMatterLines}\n---\n\n# {$this->title}\n\n{$this->content}\n";
    }
}
