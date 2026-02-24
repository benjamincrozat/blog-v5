<?php

namespace App\Filament\Resources\Posts\Pages;

use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Posts\Actions\RecordActions;

/**
 * Configures the edit post Filament page.
 */
class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions() : array
    {
        return [
            ActionGroup::make(RecordActions::configure()),
        ];
    }
}
