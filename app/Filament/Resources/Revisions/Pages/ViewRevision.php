<?php

namespace App\Filament\Resources\Revisions\Pages;

use App\Models\Revision;
use Illuminate\Support\Js;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\IconPosition;
use App\Filament\Resources\Revisions\RevisionResource;

class ViewRevision extends ViewRecord
{
    protected static string $resource = RevisionResource::class;

    protected function getHeaderActions() : array
    {
        return [
            ActionGroup::make([
                $this->makeCopyAction('title'),
                $this->makeCopyAction('description'),
                $this->makeCopyAction('content'),
            ])
                ->label('Copy')
                ->icon('heroicon-o-chevron-down')
                ->iconPosition(IconPosition::After)
                ->button()
                ->color('gray'),

            Action::make('complete')
                ->icon('heroicon-o-check')
                ->action(function (Revision $record) {
                    $record->update(['completed_at' => now()]);

                    Notification::make()
                        ->title('Revision marked as completed')
                        ->success()
                        ->send();

                    $this->redirect(RevisionResource::getUrl('index'));
                }),

            DeleteAction::make(),
        ];
    }

    protected function makeCopyAction(string $field) : Action
    {
        $label = str_replace('_', ' ', $field);
        $labelTitleCase = ucwords($label);
        $labelLowerCase = strtolower($label);

        return Action::make("copy_{$field}")
            ->label("Copy {$labelTitleCase}")
            ->alpineClickHandler(fn (Revision $record) => $this->copyToClipboardScript($record->data[$field] ?? null, "{$labelTitleCase} copied to your clipboard"))
            ->disabled(fn (Revision $record) : bool => blank($record->data[$field] ?? null));
    }

    protected function copyToClipboardScript(?string $value, string $message) : string
    {
        $valueJs = Js::from($value ?? '');
        $messageJs = Js::from($message);

        return <<<JS
            window.navigator.clipboard.writeText({$valueJs});
            \$tooltip({$messageJs}, {
                theme: \$store.theme,
                timeout: 2000,
            });
        JS;
    }
}
