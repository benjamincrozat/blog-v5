<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Models\Post;
use Illuminate\Support\Js;
use App\Jobs\RecommendPosts;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions() : array
    {
        return [
            ActionGroup::make([
                Action::make('open')
                    ->label('Open')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Post $record) => route('posts.show', $record), shouldOpenInNewTab: true),

                Action::make('copy')
                    ->label('Copy as Markdown')
                    ->icon('heroicon-o-clipboard-document')
                    ->alpineClickHandler(fn (Post $record) => 'window.navigator.clipboard.writeText(' . Js::from($record->toMarkdown()) . ')'),

                DeleteAction::make(),
            ]),
        ];
    }

    protected function afterSave() : void
    {
        if (! $this->record->recommendations) {
            RecommendPosts::dispatch($this->record);
        }
    }
}
