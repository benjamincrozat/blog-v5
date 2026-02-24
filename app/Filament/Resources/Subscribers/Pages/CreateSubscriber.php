<?php

namespace App\Filament\Resources\Subscribers\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Subscribers\SubscriberResource;

/**
 * Configures the create subscriber Filament page.
 */
class CreateSubscriber extends CreateRecord
{
    protected static string $resource = SubscriberResource::class;
}
