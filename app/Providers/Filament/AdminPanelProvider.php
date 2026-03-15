<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use App\Filament\Widgets\PostsStats;
use Filament\Http\Middleware\Authenticate;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

/**
 * Configures the Filament admin panel, middleware stack, and widgets.
 */
class AdminPanelProvider extends PanelProvider
{
    public function boot() : void
    {
        DateTimePicker::configureUsing(fn (DateTimePicker $dateTimePicker) => $dateTimePicker->defaultDateDisplayFormat('Y/m/d'));

        DateTimePicker::configureUsing(fn (DateTimePicker $dateTimePicker) => $dateTimePicker->defaultDateTimeDisplayFormat('Y/m/d H:i'));

        DateTimePicker::configureUsing(fn (DateTimePicker $dateTimePicker) => $dateTimePicker->defaultDateTimeWithSecondsDisplayFormat('Y/m/d H:i:s'));
    }

    public function panel(Panel $panel) : Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                AccountWidget::class,
                PostsStats::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->databaseNotifications()
            ->sidebarCollapsibleOnDesktop()
            ->spa();
    }
}
