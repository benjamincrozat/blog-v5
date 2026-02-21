<?php

namespace App\Filament\Resources\Revisions\Schemas;

use App\Models\Revision;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\TextEntry;

/**
 * Defines the RevisionInfolist implementation.
 */
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
                            ->url(
                                fn (Revision $record) => route('filament.admin.resources.posts.edit', $record->report->post),
                            ),

                        TextEntry::make('created_at')
                            ->dateTime()
                            ->label('Creation Date'),

                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->label('Last Modification Date'),

                        TextEntry::make('title')
                            ->state(fn (Revision $record) => $record->data['title']),

                        TextEntry::make('description')
                            ->state(fn (Revision $record) => $record->data['description']),
                    ])
                    ->columnSpanFull(),

                TextEntry::make('content')
                    ->state(fn (Revision $record) => $record->data['content'])
                    ->markdown()
                    ->copyable()
                    ->columnSpan(2),
            ])
            ->columns(3);
    }
}
