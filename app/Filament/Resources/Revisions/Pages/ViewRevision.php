<?php

namespace App\Filament\Resources\Revisions\Pages;

use App\Filament\Resources\Revisions\RevisionResource;
use App\Models\Revision;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Js;

class ViewRevision extends ViewRecord
{
    protected static string $resource = RevisionResource::class;

    protected function getHeaderActions() : array
    {
        return [
            $this->makeCopyMarkdownAction(),
            $this->makeCopyAction('title'),
            $this->makeCopyAction('description'),
            $this->makeCopyAction('content'),

            Action::make('complete')
                ->label('Mark as completed')
                ->tooltip('Mark as completed')
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
            ->tooltip("Copy the {$labelLowerCase} to your clipboard")
            ->color('gray')
            ->icon('heroicon-o-clipboard-document')
            ->alpineClickHandler(fn (Revision $record) => $this->copyToClipboardScript($record->data[$field] ?? null, "{$labelTitleCase} copied to your clipboard"))
            ->disabled(fn (Revision $record) : bool => blank($record->data[$field] ?? null));
    }

    protected function makeCopyMarkdownAction() : Action
    {
        $message = 'Revision content copied to your clipboard';

        return Action::make('copy')
            ->label('Copy as Markdown')
            ->tooltip('Copy the content as Markdown to your clipboard')
            ->color('gray')
            ->icon('heroicon-o-clipboard-document')
            ->alpineClickHandler(fn (Revision $record) => $this->copyToClipboardScript($record->data['content'] ?? null, $message))
            ->disabled(fn (Revision $record) : bool => blank($record->data['content'] ?? null));
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
