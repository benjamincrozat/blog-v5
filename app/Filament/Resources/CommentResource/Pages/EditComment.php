<?php

namespace App\Filament\Resources\CommentResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CommentResource;

/**
 * Defines the EditComment implementation.
 */
class EditComment extends EditRecord
{
    protected static string $resource = CommentResource::class;

    protected function getHeaderActions() : array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
