<?php

namespace App\Filament\Resources\Reports\Tables;

use App\Models\Report;
use App\Jobs\RevisePost;
use Filament\Tables\Table;
use Illuminate\Support\Js;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Defines the ReportsTable implementation.
 */
class ReportsTable
{
    public static function configure(Table $table) : Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('post.title'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Creation Date'),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Last Modification Date'),
            ])
            ->filters([
                SelectFilter::make('completion_status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                    ])
                    ->query(function (Builder $query, array $data) {
                        return match ($data['value']) {
                            'completed' => $query->whereNotNull('completed_at'),
                            'pending' => $query->whereNull('completed_at'),
                            default => $query,
                        };
                    })
                    ->default('pending'),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),

                    Action::make('Copy as Markdown')
                        ->icon('heroicon-o-clipboard-document')
                        ->alpineClickHandler(fn (Report $record) => 'window.navigator.clipboard.writeText(' . Js::from($record->content) . ')'),

                    Action::make('Implement editor feedback')
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
                        })
                        ->icon('heroicon-o-arrow-path'),

                    Action::make('Mark as completed')
                        ->icon('heroicon-o-check')
                        ->action(function (Report $record) {
                            $record->update(['completed_at' => now()]);
                        }),

                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
