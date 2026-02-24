<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Configures the create user Filament page.
 */
class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
