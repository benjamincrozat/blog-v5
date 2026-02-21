<?php

namespace App\Filament\Resources\Subscribers\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Subscribers\SubscriberResource;

/**
 * Defines the CreateSubscriber implementation.
 */
class CreateSubscriber extends CreateRecord
{
    protected static string $resource = SubscriberResource::class;
}
