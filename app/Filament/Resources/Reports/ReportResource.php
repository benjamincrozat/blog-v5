<?php

namespace App\Filament\Resources\Reports;

use App\Models\Report;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Reports\Pages\ViewReport;
use App\Filament\Resources\Reports\Pages\ListReports;
use App\Filament\Resources\Reports\Tables\ReportsTable;
use App\Filament\Resources\Reports\Schemas\ReportInfolist;

/**
 * Defines the ReportResource implementation.
 */
class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Blog';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocument;

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'title';

    public static function infolist(Schema $schema) : Schema
    {
        return ReportInfolist::configure($schema);
    }

    public static function table(Table $table) : Table
    {
        return ReportsTable::configure($table);
    }

    public static function getRelations() : array
    {
        return [];
    }

    public static function getPages() : array
    {
        return [
            'index' => ListReports::route('/'),
            'view' => ViewReport::route('/{record}'),
        ];
    }
}
