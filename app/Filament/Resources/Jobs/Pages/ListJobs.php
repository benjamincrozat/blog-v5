<?php

namespace App\Filament\Resources\Jobs\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Jobs\JobResource;

/**
 * Defines the ListJobs implementation.
 */
class ListJobs extends ListRecords
{
    protected static string $resource = JobResource::class;

    protected function getHeaderActions() : array
    {
        return [
            CreateAction::make(),
        ];
    }
}
