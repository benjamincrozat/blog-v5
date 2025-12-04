<?php

namespace App\Models;

use App\Markdown\Lightdown;
use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    /** @use HasFactory<CommentFactory> */
    use HasFactory, SoftDeletes;

    protected $with = [
        'user',
        'children',
        'children.user',
    ];

    protected function casts() : array
    {
        return [
            'modified_at' => 'datetime',
        ];
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault(
            fn () => new User([
                'name' => 'Deleted user',
                'github_login' => 'deleted-user',
                'github_data' => [
                    'user' => [
                        'html_url' => '#',
                    ],
                ],
            ]),
        );
    }

    public function post() : BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function parent() : BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function children() : HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function stripped() : Attribute
    {
        return Attribute::make(
            fn () => strip_tags(Lightdown::parse($this->content)),
        )->shouldCache();
    }

    public function truncated() : Attribute
    {
        return Attribute::make(
            function () {
                $stripped = strip_tags(Lightdown::parse($this->content));

                return trim(
                    strlen($stripped) > 100
                        ? rtrim(substr($stripped, 0, 100), '.') . '…'
                        : $stripped
                );
            },
        )->shouldCache();
    }

    public function deleteWithChildren() : self
    {
        $this->children->each(
            fn (Comment $comment) => $comment->deleteWithChildren()
        );

        $this->delete();

        return $this;
    }
}
