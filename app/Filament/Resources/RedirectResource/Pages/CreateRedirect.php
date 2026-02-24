<?php

namespace App\Filament\Resources\RedirectResource\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\RedirectResource;

/**
 * Configures the create redirect Filament page.
 */
class CreateRedirect extends CreateRecord
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
                ->extraAttributes([
                    'x-on:click' => "navigator.clipboard.writeText(document.querySelector('input[name=to]')?.value ?? ''); this.innerText='copied'; setTimeout(() => { this.innerText='copy'; }, 2000);",
                ]),
        ];
    }
}
