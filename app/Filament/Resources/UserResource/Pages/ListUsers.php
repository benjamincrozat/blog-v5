<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;

/**
 * Configures the list users Filament page.
 */
class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions() : array
    {
        return [
            CreateAction::make(),
        ];
    }
}
