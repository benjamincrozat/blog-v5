<?php

namespace App\Filament\Resources;

use App\Models\ShortUrl;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\ShortUrlResource\Pages\EditShortUrl;
use App\Filament\Resources\ShortUrlResource\Pages\ListShortUrls;
use App\Filament\Resources\ShortUrlResource\Pages\CreateShortUrl;

/**
 * Defines the ShortUrlResource implementation.
 */
class ShortUrlResource extends Resource
{
    protected static ?string $model = ShortUrl::class;

    protected static ?string $modelLabel = 'Short URL';

    protected static string|\UnitEnum|null $navigationGroup = 'Others';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrows-pointing-in';

    protected static ?string $recordTitleAttribute = 'code';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([
                TextInput::make('url')
                    ->required()
                    ->maxLength(255)
                    ->rules('url')
                    ->label('URL')
                    ->columnSpanFull(),

                TextInput::make('code')
                    ->nullable()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->helperText('Leave blank to generate a random code.'),
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('link')
                    ->url(fn (ShortUrl $record) => $record->link)
                    ->openUrlInNewTab(),

                TextColumn::make('url')
                    ->searchable()
                    ->label('URL'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Creation Date'),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Modification Date')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages() : array
    {
        return [
            'index' => ListShortUrls::route('/'),
            'create' => CreateShortUrl::route('/create'),
            'edit' => EditShortUrl::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes() : array
    {
        return [
            'url',
            'code',
        ];
    }

    public static function getGlobalSearchResultDetails($record) : array
    {
        return [
            'URL' => $record->url,
        ];
    }
}
