<?php

namespace App\Filament\Resources\ShortUrlResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ShortUrlResource;

/**
 * Configures the list short urls Filament page.
 */
class ListShortUrls extends ListRecords
{
    protected static string $resource = ShortUrlResource::class;

    protected function getHeaderActions() : array
    {
        return [
            CreateAction::make(),
        ];
    }
}
