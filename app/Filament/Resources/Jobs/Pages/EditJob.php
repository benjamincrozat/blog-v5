<?php

namespace App\Filament\Resources\Jobs\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Jobs\JobResource;

/**
 * Defines the EditJob implementation.
 */
class EditJob extends EditRecord
{
    protected static string $resource = JobResource::class;

    protected function getHeaderActions() : array
    {
        return [
            ActionGroup::make([
                Action::make('open')
                    ->url(fn () => route('jobs.show', $this->record))
                    ->label('Open')
                    ->icon('heroicon-o-arrow-top-right-on-square'),

                Action::make('openOriginal')
                    ->url(fn () => $this->record?->url, shouldOpenInNewTab: true)
                    ->label('Open the original website')
                    ->hidden(fn () => blank($this->record?->url))
                    ->icon('heroicon-o-arrow-right-end-on-rectangle'),
            ]),

            DeleteAction::make(),
        ];
    }
}
