<?php

namespace App\Filament\Resources\Posts\Actions;

use App\Models\Post;
use Illuminate\Support\Js;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use App\Filament\Resources\Posts\Pages\EditPost as EditPostPage;

/**
 * Defines the RecordActions implementation.
 */
class RecordActions
{
    public static function configure() : array
    {
        return [
            Action::make('open')
                ->label('Open')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn (Post $record) => route('posts.show', $record), shouldOpenInNewTab: true),

            Action::make('copy_url')
                ->label('Copy URL')
                ->icon('heroicon-o-link')
                ->alpineClickHandler(fn (Post $record) => 'window.navigator.clipboard.writeText(' . Js::from(route('posts.show', $record)) . ')'),

            Action::make('copy')
                ->label('Copy as Markdown')
                ->icon('heroicon-o-clipboard-document')
                ->alpineClickHandler(fn (Post $record) => 'window.navigator.clipboard.writeText(' . Js::from($record->toMarkdown()) . ')'),

            Action::make('check_in_gsc')
                ->label('Check in GSC')
                ->icon('heroicon-o-chart-bar')
                ->url(function (Post $record) {
                    $domain = preg_replace('/https?:\/\//', '', config('app.url'));

                    return "https://search.google.com/search-console/performance/search-analytics?resource_id=sc-domain%3A$domain&breakdown=query&page=!" . rawurlencode(route('posts.show', $record));
                }, shouldOpenInNewTab: true),

            EditAction::make()
                ->hidden(fn ($livewire) => $livewire instanceof EditPostPage)
                ->icon('heroicon-o-pencil-square'),

            DeleteAction::make()
                ->icon('heroicon-o-trash'),

            ForceDeleteAction::make(),

            RestoreAction::make(),
        ];
    }
}
