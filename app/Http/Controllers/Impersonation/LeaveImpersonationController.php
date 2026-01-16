<?php

namespace App\Http\Controllers\Impersonation;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Lab404\Impersonate\Services\ImpersonateManager;

/**
 * Ends an impersonation session and redirects to a safe return URL.
 *
 * Extracted to keep impersonation flow isolated from routing and UI logic.
 * Callers can rely on a redirect even when not impersonating.
 */
class LeaveImpersonationController extends Controller
{
    public function __invoke() : RedirectResponse
    {
        if (app(ImpersonateManager::class)->isImpersonating()) {
            app(ImpersonateManager::class)->leave();
        }

        $redirectTo = session()->pull('impersonate.return')
            ?? request()->headers->get('referer')
            ?? route('filament.admin.resources.users.index');

        return redirect()->to($redirectTo);
    }
}
