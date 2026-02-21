<?php

namespace App\Filament\Resources\Posts\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Posts\PostResource;

/**
 * Defines the ListPosts implementation.
 */
class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions() : array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getTableQuery() : Builder
    {
        return parent::getTableQuery()
            ->with([
                'user:id,name',
                'categories:id,name',
            ]);
    }
}
