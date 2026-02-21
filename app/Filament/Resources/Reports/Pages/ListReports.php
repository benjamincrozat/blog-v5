<?php

namespace App\Filament\Resources\Reports\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Reports\ReportResource;

/**
 * Defines the ListReports implementation.
 */
class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions() : array
    {
        return [
            CreateAction::make(),
        ];
    }
}
