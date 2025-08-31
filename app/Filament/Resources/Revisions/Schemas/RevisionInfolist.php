<?php

namespace App\Filament\Resources\Revisions\Schemas;

use App\Models\Revision;
use Illuminate\Support\Js;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\TextEntry;

class RevisionInfolist
{
    public static function configure(Schema $schema) : Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->schema([
                        TextEntry::make('report.post.title')
                            ->label('Revision for')
                            ->afterContent(fn (TextEntry $component) => Action::make('copy_revision_for')
                                ->label('Copy')
                                ->icon('heroicon-m-clipboard')
                                ->size('xs')
                                ->extraAttributes(fn () => [
                                    'x-on:click' => 'window.navigator.clipboard.writeText('
                                        . Js::from($component->getState())
                                        . '); $tooltip(\'Copied to clipboard\', { theme: $store.theme })',
                                ])),

                        TextEntry::make('created_at')
                            ->dateTime()
                            ->label('Creation Date')
                            ->afterContent(fn (TextEntry $component) => Action::make('copy_creation_date')
                                ->label('Copy')
                                ->icon('heroicon-m-clipboard')
                                ->size('xs')
                                ->extraAttributes(fn () => [
                                    'x-on:click' => 'window.navigator.clipboard.writeText('
                                        . Js::from((string) $component->formatState($component->getState()))
                                        . '); $tooltip(\'Copied to clipboard\', { theme: $store.theme })',
                                ])),

                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->label('Last Modification Date')
                            ->afterContent(fn (TextEntry $component) => Action::make('copy_last_modified')
                                ->label('Copy')
                                ->icon('heroicon-m-clipboard')
                                ->size('xs')
                                ->extraAttributes(fn () => [
                                    'x-on:click' => 'window.navigator.clipboard.writeText('
                                        . Js::from((string) $component->formatState($component->getState()))
                                        . '); $tooltip(\'Copied to clipboard\', { theme: $store.theme })',
                                ])),

                        TextEntry::make('title')
                            ->state(fn (Revision $record) => $record->data['title'])
                            ->afterContent(fn (TextEntry $component) => Action::make('copy_title')
                                ->label('Copy')
                                ->icon('heroicon-m-clipboard')
                                ->size('xs')
                                ->extraAttributes(fn () => [
                                    'x-on:click' => 'window.navigator.clipboard.writeText('
                                        . Js::from($component->getState())
                                        . '); $tooltip(\'Copied to clipboard\', { theme: $store.theme })',
                                ])),

                        TextEntry::make('description')
                            ->state(fn (Revision $record) => $record->data['description'])
                            ->afterContent(fn (TextEntry $component) => Action::make('copy_description')
                                ->label('Copy')
                                ->icon('heroicon-m-clipboard')
                                ->size('xs')
                                ->extraAttributes(fn () => [
                                    'x-on:click' => 'window.navigator.clipboard.writeText('
                                        . Js::from($component->getState())
                                        . '); $tooltip(\'Copied to clipboard\', { theme: $store.theme })',
                                ])),
                    ])
                    ->columnSpanFull(),

                TextEntry::make('content')
                    ->state(fn (Revision $record) => $record->data['content'])
                    ->markdown()
                    ->afterContent(fn (TextEntry $component) => Action::make('copy_content')
                        ->label('Copy')
                        ->icon('heroicon-m-clipboard')
                        ->size('xs')
                        ->extraAttributes(fn () => [
                            'x-on:click' => 'window.navigator.clipboard.writeText('
                                . Js::from($component->getState())
                                . '); $tooltip(\'Copied to clipboard\', { theme: $store.theme })',
                        ]))
                    ->columnSpan(2),
            ])
            ->columns(3);
    }
}
