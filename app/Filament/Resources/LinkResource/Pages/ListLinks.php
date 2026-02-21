<?php

namespace App\Filament\Resources\LinkResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\LinkResource;
use Filament\Resources\Pages\ListRecords;

/**
 * Defines the ListLinks implementation.
 */
class ListLinks extends ListRecords
{
    protected static string $resource = LinkResource::class;

    protected function getHeaderActions() : array
    {
        return [
            CreateAction::make(),
        ];
    }
}
