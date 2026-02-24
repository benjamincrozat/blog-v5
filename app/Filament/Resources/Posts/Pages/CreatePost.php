<?php

namespace App\Filament\Resources\Posts\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Posts\PostResource;

/**
 * Configures the create post Filament page.
 */
class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
}
