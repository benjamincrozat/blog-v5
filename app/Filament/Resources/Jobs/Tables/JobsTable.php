<?php

namespace App\Filament\Resources\Jobs\Tables;

use App\Models\Job;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Support\Number;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;

/**
 * Defines the JobsTable implementation.
 */
class JobsTable
{
    public static function configure(Table $table) : Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Split::make([
                    Stack::make([
                        TextColumn::make('title')
                            ->state(fn (Job $record) => "<strong>$record->title</strong>")
                            ->html(),

                        TextColumn::make('setting')
                            ->state(fn (Job $record) => ucfirst($record->setting)),

                        TextColumn::make('salary')
                            ->state(function (Job $record) {
                                if ($record->min_salary && $record->max_salary) {
                                    $currency = $record->currency ?? 'USD';

                                    return Number::currency($record->min_salary, $currency) . '—' . Number::currency($record->max_salary, $currency);
                                }

                                if ($record->min_salary && ! $record->max_salary) {
                                    $currency = $record->currency ?? 'USD';

                                    return 'From ' . Number::currency($record->min_salary, $currency);
                                }

                                if ($record->max_salary && ! $record->min_salary) {
                                    $currency = $record->currency ?? 'USD';

                                    return 'Up to ' . Number::currency($record->max_salary, $currency);
                                }

                                return null;
                            }),

                        TextColumn::make('equity')
                            ->state(fn (Job $record) => 'Equity: ' . ($record->equity ? '<strong>Yes</strong>' : 'No'))
                            ->html(),
                    ]),

                    TextColumn::make('source'),

                    TextColumn::make('created_at')
                        ->dateTime(),
                ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('open')
                        ->url(fn (Job $record) => route('jobs.show', $record), shouldOpenInNewTab: true)
                        ->label('Open')
                        ->icon('heroicon-o-arrow-top-right-on-square'),

                    Action::make('open')
                        ->url(fn (Job $record) => $record->url, shouldOpenInNewTab: true)
                        ->label('Open the original website')
                        ->hidden(fn (Job $record) => ! $record->url)
                        ->icon('heroicon-o-arrow-right-end-on-rectangle'),

                    EditAction::make(),

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
