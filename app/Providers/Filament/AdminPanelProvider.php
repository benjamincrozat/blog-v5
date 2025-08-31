<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use App\Filament\Widgets\VisitorStats;
use Filament\Navigation\NavigationItem;
use Filament\Widgets\FilamentInfoWidget;
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
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
                VisitorStats::class,
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
            ->spa()
            ->navigationItems([
                NavigationItem::make('Go to site')
                    ->url('/')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->sort(0),
                NavigationItem::make('Horizon')
                    ->url('/horizon')
                    ->icon('icon-horizon')
                    ->sort(1),
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Go to site')
                    ->url('/')
                    ->icon('heroicon-o-arrow-right-circle'),
                MenuItem::make()
                    ->label('Horizon')
                    ->url('/horizon')
                    ->icon('icon-horizon'),
            ]);
    }
}
