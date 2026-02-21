<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Defines the CreateUser implementation.
 */
class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
