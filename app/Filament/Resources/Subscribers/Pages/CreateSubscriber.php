<?php

namespace App\Filament\Resources\Subscribers\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Subscribers\SubscriberResource;

class CreateSubscriber extends CreateRecord
{
    protected static string $resource = SubscriberResource::class;
}
