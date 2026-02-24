<?php

namespace App\Filament\Resources\Posts\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Posts\PostResource;

/**
 * Defines the CreatePost implementation.
 */
class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
}
