<?php

namespace App\Filament\Resources\Companies\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Companies\CompanyResource;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;
}
