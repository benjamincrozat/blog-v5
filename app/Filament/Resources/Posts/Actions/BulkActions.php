<?php

namespace App\Filament\Resources\Posts\Actions;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;

/**
 * Provides reusable bulk actions Filament actions.
 */
class BulkActions
{
    public static function configure() : array
    {
        return [
            DeleteBulkAction::make(),

            ForceDeleteBulkAction::make(),

            RestoreBulkAction::make(),
        ];
    }
}
