<?php

namespace App\Filament\Resources\Jobs\Pages;

use App\Jobs\ReviseJob;
use App\Jobs\ScrapeJob;
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
                Action::make('open')
                    ->url(fn () => route('jobs.show', $this->record))
                    ->label('Open')
                    ->icon('heroicon-o-arrow-top-right-on-square'),

                Action::make('openOriginal')
                    ->url(fn () => $this->record?->url, shouldOpenInNewTab: true)
                    ->label('Open the original website')
                    ->hidden(fn () => blank($this->record?->url))
                    ->icon('heroicon-o-arrow-right-end-on-rectangle'),

                Action::make('scrape')
                    ->hidden(fn () => blank($this->record?->url))
                    ->label('Scrape the job again')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        ScrapeJob::dispatch($this->record->url);

                        Notification::make()
                            ->title('The job has been queued for scraping.')
                            ->success()
                            ->send();
                    }),

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
