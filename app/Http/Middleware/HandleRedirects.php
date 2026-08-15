<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Redirect;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Normalizes public URLs and sends old root-level post URLs to current slugs.
 *
 * Before normal request handling, it checks only a non-empty path with one part.
 * A stored match returns a permanent 301 and keeps the original query string.
 * Safe requests with a trailing slash also return a permanent redirect without
 * that slash. Other paths continue unchanged.
 */
class HandleRedirects
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response
    {
        $path = trim($request->path(), '/');

        // Handle root-level post redirects: /{slug}
        if ('' !== $path && ! str_contains($path, '/') && ($redirect = Redirect::query()->where('from', $path)->first())) {
            $target = '/' . ltrim($redirect->to, '/');

            if ($request->getQueryString()) {
                $target .= '?' . $request->getQueryString();
            }

            return redirect($target, status: 301);
        }

        $pathInfo = $request->getPathInfo();

        if ($request->isMethodSafe() && '/' !== $pathInfo && str_ends_with($pathInfo, '/')) {
            $target = rtrim($pathInfo, '/');

            if ($request->getQueryString()) {
                $target .= '?' . $request->getQueryString();
            }

            return redirect($target, status: 301);
        }

        return $next($request);
    }
}
