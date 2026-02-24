<?php

namespace App\Filament\Resources\RedirectResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\RedirectResource;

/**
 * Configures the list redirects Filament page.
 */
class ListRedirects extends ListRecords
{
    protected static string $resource = RedirectResource::class;

    protected function getHeaderActions() : array
    {
        return [
            CreateAction::make(),
        ];
    }
}
