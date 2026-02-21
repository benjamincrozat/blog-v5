<?php

namespace App\Filament\Resources\Categories\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Categories\CategoryResource;

/**
 * Defines the CreateCategory implementation.
 */
class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
}
