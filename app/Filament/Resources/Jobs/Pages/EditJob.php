<?php

namespace App\Filament\Resources\Jobs\Pages;

use App\Jobs\ReviseJob;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Jobs\JobResource;

class EditJob extends EditRecord
{
    protected static string $resource = JobResource::class;

    protected function getHeaderActions() : array
    {
        return [
            ActionGroup::make([
                Action::make('revise')
                    ->icon('heroicon-o-arrow-path')
                    ->hidden(fn () => ! (bool) $this->record?->html)
                    ->schema([
                        Textarea::make('additional_instructions')
                            ->label('Additional instructions')
                            ->placeholder('Optional hints for the model.')
                            ->rows(4)
                            ->nullable(),
                    ])
                    ->modalSubmitActionLabel('Revise')
                    ->action(function (array $data) {
                        ReviseJob::dispatch($this->record, $data['additional_instructions'] ?? null);

                        Notification::make()
                            ->title('The job has been queued for revision.')
                            ->success()
                            ->send();
                    }),
            ]),

            DeleteAction::make(),
        ];
    }
}
