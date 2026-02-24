<?php

namespace App\Filament\Resources\Jobs\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Jobs\JobResource;

/**
 * Configures the list jobs Filament page.
 */
class ListJobs extends ListRecords
{
    protected static string $resource = JobResource::class;
}
