<?php

namespace App\Filament\Resources\Revisions;

use App\Models\Revision;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Revisions\Pages\ViewRevision;
use App\Filament\Resources\Revisions\Pages\ListRevisions;
use App\Filament\Resources\Revisions\Tables\RevisionsTable;
use App\Filament\Resources\Revisions\Schemas\RevisionInfolist;

/**
 * Defines the RevisionResource implementation.
 */
class RevisionResource extends Resource
{
    protected static ?string $model = Revision::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Blog';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'title';

    public static function infolist(Schema $schema) : Schema
    {
        return RevisionInfolist::configure($schema);
    }

    public static function table(Table $table) : Table
    {
        return RevisionsTable::configure($table);
    }

    public static function getRelations() : array
    {
        return [];
    }

    public static function getPages() : array
    {
        return [
            'index' => ListRevisions::route('/'),
            'view' => ViewRevision::route('/{record}'),
        ];
    }
}
