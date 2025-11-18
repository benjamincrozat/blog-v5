<?php

namespace App\Filament\Resources\Jobs\Tables;

use App\Models\Job;
use App\Jobs\ReviseJob;
use App\Jobs\ScrapeJob;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Support\Number;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Collection;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;

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

                    Action::make('scrape')
                        ->action(function (Job $record) {
                            ScrapeJob::dispatch($record->url);

                            Notification::make()
                                ->title('The job has been queued for scraping.')
                                ->success()
                                ->send();
                        })
                        ->hidden(fn (Job $record) => ! $record->url)
                        ->label('Scrape the job again')
                        ->icon('heroicon-o-arrow-down-tray'),

                    Action::make('revise')
                        ->schema([
                            Textarea::make('additional_instructions')
                                ->nullable(),
                        ])
                        ->modalSubmitActionLabel('Revise')
                        ->action(function (Job $record, array $data) {
                            ReviseJob::dispatch($record, $data['additional_instructions'] ?? null);

                            Notification::make()
                                ->title('The job has been queued for revision.')
                                ->success()
                                ->send();
                        })
                        ->hidden(fn (Job $record) => ! $record->html)
                        ->icon('heroicon-o-arrow-path'),

                    EditAction::make(),

                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('revise')
                        ->label('Revise')
                        ->icon('heroicon-o-arrow-path')
                        ->modalSubmitActionLabel('Revise')
                        ->action(function (Collection $records) {
                            $eligible = $records->filter(fn (Job $record) => (bool) $record->html);

                            if ($eligible->isEmpty()) {
                                Notification::make()
                                    ->title('No selected jobs can be revised.')
                                    ->body('Select jobs that have HTML content before running this action.')
                                    ->warning()
                                    ->send();

                                return;
                            }

                            $eligible->each(fn (Job $record) => ReviseJob::dispatch($record));

                            $title = trans_choice('The job has been queued for revision.|The jobs have been queued for revision.', $eligible->count());

                            $body = $eligible->count() === $records->count()
                                ? null
                                : 'Some selected jobs were skipped because they have no HTML content.';

                            $notification = Notification::make()
                                ->title($title)
                                ->success();

                            if ($body) {
                                $notification->body($body);
                            }

                            $notification->send();
                        }),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
