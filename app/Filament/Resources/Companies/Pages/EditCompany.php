<?php

namespace App\Filament\Resources\Companies\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Companies\CompanyResource;

/**
 * Configures the edit company Filament page.
 */
class EditCompany extends EditRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions() : array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
