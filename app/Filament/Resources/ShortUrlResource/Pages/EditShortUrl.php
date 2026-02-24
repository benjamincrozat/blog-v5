<?php

namespace App\Filament\Resources\ShortUrlResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ShortUrlResource;

/**
 * Configures the edit short url Filament page.
 */
class EditShortUrl extends EditRecord
{
    protected static string $resource = ShortUrlResource::class;

    protected function getHeaderActions() : array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
