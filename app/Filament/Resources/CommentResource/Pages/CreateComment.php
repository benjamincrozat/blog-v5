<?php

namespace App\Filament\Resources\CommentResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CommentResource;

/**
 * Configures the create comment Filament page.
 */
class CreateComment extends CreateRecord
{
    protected static string $resource = CommentResource::class;
}
