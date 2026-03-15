<?php

namespace App\Filament\Resources\Posts\Pages;

use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Posts\PostResource;

/**
 * Configures the list posts Filament page.
 */
class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions() : array
    {
        return [];
    }

    protected function getTableQuery() : Builder
    {
        return parent::getTableQuery()
            ->select([
                'posts.id',
                'posts.slug',
                'posts.title',
                'posts.user_id',
                'posts.image_path',
                'posts.image_disk',
                'posts.canonical_url',
                'posts.sessions_count',
                'posts.published_at',
                'posts.modified_at',
                'posts.deleted_at',
            ])
            ->with([
                'user:id,name,github_login',
                'categories:id,name,slug',
            ]);
    }
}
