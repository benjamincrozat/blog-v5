<?php

namespace App\Filament\Resources;

use App\Models\Metric;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\MetricResource\Pages\ViewMetric;
use App\Filament\Resources\MetricResource\Pages\ListMetrics;

/**
 * Configures the metric resource Filament resource.
 */
class MetricResource extends Resource
{
    protected static ?string $model = Metric::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Others';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'key';

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Textarea::make('value')
                    ->required()
                    ->columnSpanFull(),

                DateTimePicker::make('created_at')
                    ->timezone('Europe/Paris')
                    ->native(false)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('key')
                    ->searchable(),

                TextColumn::make('value')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Creation Date'),
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('')
                    ->button()
                    ->outlined()
                    ->size('xs'),

                DeleteAction::make()
                    ->icon('')
                    ->button()
                    ->outlined()
                    ->size('xs'),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
                ForceDeleteBulkAction::make(),
                RestoreBulkAction::make(),
            ]);
    }

    public static function getPages() : array
    {
        return [
            'index' => ListMetrics::route('/'),
            'view' => ViewMetric::route('/{record}'),
        ];
    }

    public static function getGloballySearchableAttributes() : array
    {
        return ['key', 'value'];
    }

    public static function getGlobalSearchResultDetails(Model $record) : array
    {
        return ['Value' => $record->value];
    }

    public static function getRecordTitle(?Model $record) : string|Htmlable|null
    {
        return "\"{$record->key}\" metric";
    }
}
