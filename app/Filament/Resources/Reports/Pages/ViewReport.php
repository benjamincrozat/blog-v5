<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Models\Report;
use App\Jobs\RevisePost;
use Illuminate\Support\Js;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Reports\ReportResource;

/**
 * Defines the ViewReport implementation.
 */
class ViewReport extends ViewRecord
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions() : array
    {
        return [
            Action::make('complete')
                ->hiddenLabel(true)
                ->tooltip('Mark as completed')
                ->icon('heroicon-o-check')
                ->color('gray')
                ->action(function (Report $record) {
                    $record->update(['completed_at' => now()]);

                    Notification::make()
                        ->title('Report marked as completed')
                        ->success()
                        ->send();

                    $this->redirect(ReportResource::getUrl('index'));
                }),

            Action::make('copy')
                ->label('Copy as Markdown')
                ->hiddenLabel(true)
                ->tooltip('Copy as Markdown')
                ->icon('heroicon-o-clipboard-document')
                ->color('gray')
                ->alpineClickHandler(fn (Report $record) => 'window.navigator.clipboard.writeText(' . Js::from($record->content) . ')'),

            Action::make('Implement')
                ->schema([
                    Textarea::make('additional_instructions')
                        ->nullable(),
                ])
                ->modalSubmitActionLabel('Implement')
                ->action(function (Report $record, array $data) {
                    RevisePost::dispatch($record->post, $record, $data['additional_instructions']);

                    Notification::make()
                        ->title('The post has been queued for revision.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
