<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides admin behavior.
 */
class Admin
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response
    {
        if ($request->user()?->isAdmin()) {
            return $next($request);
        }

        abort(403);
    }
}
