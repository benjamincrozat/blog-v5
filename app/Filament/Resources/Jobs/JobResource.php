<?php

namespace App\Filament\Resources\Jobs;

use BackedEnum;
use App\Models\Job;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Jobs\Pages\EditJob;
use App\Filament\Resources\Jobs\Pages\ListJobs;
use App\Filament\Resources\Jobs\Schemas\JobForm;
use App\Filament\Resources\Jobs\Tables\JobsTable;

/**
 * Configures the job resource Filament resource.
 */
class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Jobs';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema) : Schema
    {
        return JobForm::configure($schema);
    }

    public static function table(Table $table) : Table
    {
        return JobsTable::configure($table);
    }

    public static function getPages() : array
    {
        return [
            'index' => ListJobs::route('/'),
            'edit' => EditJob::route('/{record}/edit'),
        ];
    }
}
