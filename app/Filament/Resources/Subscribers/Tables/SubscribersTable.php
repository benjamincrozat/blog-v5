<?php

namespace App\Filament\Resources\Subscribers\Tables;

use App\Models\Subscriber;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;

class SubscribersTable
{
    public static function configure(Table $table) : Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('tags')
                    ->getStateUsing(
                        fn (Subscriber $record) => $record->tags->pluck('name')->join(',')
                    )
                    ->badge(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (Subscriber $record) => $record->needsConfirmation() ? 'warning' : 'success')
                    ->state(fn (Subscriber $record) => $record->needsConfirmation() ? 'Pending' : 'Confirmed'),

                TextColumn::make('confirmation_sent_at')
                    ->since()
                    ->tooltip(fn (Subscriber $record) => optional($record->confirmation_sent_at)?->toDayDateTimeString())
                    ->sortable()
                    ->label('Confirmation Sent Date'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Creation Date'),
            ])
            ->filters([
                TernaryFilter::make('confirmed')
                    ->placeholder('Any status')
                    ->trueLabel('Confirmed')
                    ->falseLabel('Pending')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('confirmed_at'),
                        false: fn (Builder $query) => $query->whereNull('confirmed_at'),
                    )
                    ->label('Confirmation Status'),

                SelectFilter::make('tags')
                    ->label('Tag')
                    ->relationship('tags', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('resend')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('gray')
                        ->visible(fn (Subscriber $record) => $record->needsConfirmation())
                        ->requiresConfirmation()
                        ->action(function (Subscriber $record) {
                            $record->sendConfirmationNotification();

                            Notification::make()
                                ->title('Confirmation email sent to ' . $record->email)
                                ->success()
                                ->send();
                        })
                        ->label('Resend confirmation'),

                    Action::make('markConfirmed')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Subscriber $record) => $record->needsConfirmation())
                        ->action(function (Subscriber $record) {
                            $record->markAsConfirmed();

                            Notification::make()
                                ->title('Subscriber marked as confirmed.')
                                ->success()
                                ->send();
                        })
                        ->label('Mark as confirmed'),

                    Action::make('markPending')
                        ->label('Set as pending')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->visible(fn (Subscriber $record) => ! $record->needsConfirmation())
                        ->requiresConfirmation()
                        ->action(function (Subscriber $record) {
                            $record->forceFill([
                                'confirmed_at' => null,
                            ])->save();

                            Notification::make()
                                ->title('Subscriber moved back to pending.')
                                ->success()
                                ->send();
                        }),

                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No subscribers yet')
            ->emptyStateDescription('New signups from the newsletter will show up here automatically.');
    }
}
