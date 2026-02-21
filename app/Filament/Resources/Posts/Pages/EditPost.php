<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Jobs\RecommendPosts;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Posts\Actions\RecordActions;

/**
 * Defines the EditPost implementation.
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

    protected function afterSave() : void
    {
        if (! $this->record->recommendations) {
            RecommendPosts::dispatch($this->record);
        }
    }
}
