<?php

namespace App\Filament\Resources\Categories\Actions;

use App\Models\Category;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Categories\Pages\EditCategory;

class RecordActions
{
    public static function configure() : array
    {
        return [
            Action::make('open')
                ->label('Open')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn (Category $record) => route('categories.show', $record), shouldOpenInNewTab: true),

            EditAction::make()
                ->hidden(fn ($livewire) => $livewire instanceof EditCategory),

            DeleteAction::make(),
        ];
    }
}
