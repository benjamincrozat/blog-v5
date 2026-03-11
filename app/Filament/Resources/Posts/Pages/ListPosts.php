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
            ->with([
                'user:id,name,github_login',
                'categories:id,name,slug',
            ]);
    }
}
