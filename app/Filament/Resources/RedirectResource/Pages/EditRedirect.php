<?php

namespace App\Filament\Resources\RedirectResource\Pages;

use Illuminate\Support\Js;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\RedirectResource;

/**
 * Defines the EditRedirect implementation.
 */
class EditRedirect extends EditRecord
{
    protected static string $resource = RedirectResource::class;

    protected function getHeaderActions() : array
    {
        return [
            Action::make('copy')
                ->label('copy')
                ->icon('')
                ->button()
                ->outlined()
                ->size('xs')
                ->color('gray')
                ->extraAttributes(fn () : array => [
                    'x-on:click' => 'navigator.clipboard.writeText(' . Js::from($this->getRecord()->to) . "); this.innerText='copied'; setTimeout(() => { this.innerText='copy'; }, 2000);",
                ]),
            DeleteAction::make(),
        ];
    }
}
