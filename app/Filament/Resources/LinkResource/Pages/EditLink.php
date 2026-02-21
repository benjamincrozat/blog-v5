<?php

namespace App\Filament\Resources\LinkResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\LinkResource;
use Filament\Resources\Pages\EditRecord;

/**
 * Defines the EditLink implementation.
 */
class EditLink extends EditRecord
{
    protected static string $resource = LinkResource::class;

    protected function getHeaderActions() : array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
