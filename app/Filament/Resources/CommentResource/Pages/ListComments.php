<?php

namespace App\Filament\Resources\CommentResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\CommentResource;

/**
 * Defines the ListComments implementation.
 */
class ListComments extends ListRecords
{
    protected static string $resource = CommentResource::class;

    protected function getHeaderActions() : array
    {
        return [
            CreateAction::make(),
        ];
    }
}
