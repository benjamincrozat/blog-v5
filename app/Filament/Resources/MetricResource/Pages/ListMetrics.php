<?php

namespace App\Filament\Resources\MetricResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\MetricResource;

/**
 * Defines the ListMetrics implementation.
 */
class ListMetrics extends ListRecords
{
    protected static string $resource = MetricResource::class;

    protected function getHeaderActions() : array
    {
        return [
            CreateAction::make(),
        ];
    }
}
