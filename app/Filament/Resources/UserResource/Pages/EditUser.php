<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;

/**
 * Configures the edit user Filament page.
 */
class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions() : array
    {
        return [
            Action::make('impersonate')
                ->label('Impersonate')
                ->icon('heroicon-o-user')
                ->outlined()
                ->color('gray')
                ->visible(fn () => Auth::user()?->canImpersonate() && $this->record->canBeImpersonated())
                ->action(function () {
                    session([
                        'impersonate.return' => request()->headers->get('referer') ?? request()->fullUrl(),
                    ]);

                    Auth::user()->impersonate($this->record);

                    return redirect()->route('home');
                }),

            DeleteAction::make(),
        ];
    }
}
