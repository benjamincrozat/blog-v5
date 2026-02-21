<?php

namespace App\Filament\Resources\Categories\Pages;

use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Categories\Actions\RecordActions;

/**
 * Defines the EditCategory implementation.
 */
class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions() : array
    {
        return [
            ActionGroup::make(RecordActions::configure()),
        ];
    }
}
