<?php

namespace App\Filament\Resources\ShortUrlResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ShortUrlResource;

/**
 * Defines the CreateShortUrl implementation.
 */
class CreateShortUrl extends CreateRecord
{
    protected static string $resource = ShortUrlResource::class;
}
