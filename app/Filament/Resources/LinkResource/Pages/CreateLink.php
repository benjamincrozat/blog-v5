<?php

namespace App\Filament\Resources\LinkResource\Pages;

use App\Filament\Resources\LinkResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Configures the create link Filament page.
 */
class CreateLink extends CreateRecord
{
    protected static string $resource = LinkResource::class;
}
