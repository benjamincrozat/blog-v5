<?php

namespace App\Models;

use App\Str;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model implements Feedable
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, SoftDeletes;

    protected $withCount = ['comments'];

    public static function booted() : void
    {
        static::creating(
            function (Post $post) {
                $post->slug ??= Str::slug($post->title);
            }
        );

        static::updating(function (Post $post) {
            if (! $post->isDirty('slug')) {
                return;
            }

            $old = $post->getOriginal('slug');

            $new = $post->slug;

            if (! filled($old) || ! filled($new) || $old === $new) {
                return;
            }

            DB::transaction(function () use ($old, $new) {
                // 1. Remove any redirect originating from the new slug. It would
                // create a loop once the slug becomes its own destination.
                Redirect::query()->where('from', $new)->delete();

                // 2. Point existing redirects that ended at
                // the old slug directly to the new slug.
                Redirect::query()->where('to', $old)->update(['to' => $new]);

                // 3. Create or update the canonical redirect from the old slug to the new slug.
                Redirect::query()->updateOrCreate(
                    ['from' => $old],
                    ['to' => $new]
                );
            });
        });
    }

    protected function casts() : array
    {
        return [
            'published_at' => 'datetime',
            'modified_at' => 'datetime',
            'recommendations' => 'collection',
        ];
    }

    #[Scope]
    protected function published(Builder $query) : void
    {
        $query->whereNotNull('published_at');
    }

    #[Scope]
    protected function unpublished(Builder $query) : void
    {
        $query->whereNull('published_at');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories() : BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function link() : HasOne
    {
        return $this->hasOne(Link::class);
    }

    public function quiz() : HasOne
    {
        return $this->hasOne(Quiz::class);
    }

    public function formattedContent() : Attribute
    {
        return Attribute::make(
            fn () => Str::markdown($this->content),
        )->shouldCache();
    }

    public function imageUrl() : Attribute
    {
        return Attribute::make(
            fn () => $this->hasImage() ? Storage::disk($this->image_disk)->url($this->image_path) : null,
        )->shouldCache();
    }

    public function readTime() : Attribute
    {
        return Attribute::make(
            fn () => ceil(str_word_count($this->content) / 200),
        )->shouldCache();
    }

    public function recommendedPosts() : Attribute
    {
        return Attribute::make(
            fn () => empty($this->recommendations) ? null : Post::query()
                ->whereIn('id', $this->recommendations->pluck('id'))
                ->get()
                ->map(function (self $post) {
                    $recommendation = collect($this->recommendations)
                        ->firstWhere('id', $post->id);

                    if (! empty($recommendation['reason'])) {
                        $post->reason = $recommendation['reason'];
                    }

                    return $post;
                }),
        )->shouldCache();
    }

    public function hasImage() : bool
    {
        return $this->image_path && $this->image_disk;
    }

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

    public function toPrompt() : string
    {
        $content = preg_replace(['/\s+/', '/\n+/'], [' ', "\n"], strip_tags($this->formatted_content, allowed_tags: ['a']));

        return <<<MARKDOWN
$this->title $content

---

Highlight the key points of this article.
MARKDOWN;
    }

    public static function getFeedItems() : Collection
    {
        return static::query()
            ->published()
            ->whereDoesntHave('link')
            ->latest('published_at')
            ->limit(50)
            ->get();
    }

    public function toFeedItem() : FeedItem
    {
        $link = route('posts.show', $this);

        return FeedItem::create()
            ->id($this->slug)
            ->title($this->title)
            ->summary(Str::markdown($this->description . <<<MARKDOWN

[Read more →]($link)

If you like my feed, follow me on [X](https://x.com/benjamincrozat), [LinkedIn](https://www.linkedin.com/in/benjamincrozat/), and [GitHub](https://github.com/benjamincrozat).
MARKDOWN ?? ''))
            ->updated($this->modified_at ?? $this->published_at)
            ->link($link)
            ->authorName($this->user->name);
    }

    public function getRouteKeyName() : string
    {
        return 'slug';
    }
}
