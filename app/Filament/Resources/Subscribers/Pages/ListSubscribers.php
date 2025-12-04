<?php

namespace App\Filament\Resources\Subscribers\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Subscribers\SubscriberResource;

class ListSubscribers extends ListRecords
{
    protected static string $resource = SubscriberResource::class;

    protected function getHeaderActions() : array
    {
        return [
            CreateAction::make()
                ->label('Add subscriber')
                ->icon('heroicon-o-user-plus'),
        ];
    }
}
