<?php

namespace App\Filament\Resources\ShortUrlResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ShortUrlResource;

/**
 * Configures the create short url Filament page.
 */
class CreateShortUrl extends CreateRecord
{
    protected static string $resource = ShortUrlResource::class;
}
