<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Redirect;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleRedirects
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
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

        // Handle job redirects: /jobs/{slug}
        if (str_starts_with($path, 'jobs/') && 1 === substr_count($path, '/')) {
            if ($redirect = Redirect::query()->where('from', $path)->first()) {
                $target = '/' . ltrim($redirect->to, '/');

                if ($request->getQueryString()) {
                    $target .= '?' . $request->getQueryString();
                }

                return redirect($target, status: 301);
            }
        }

        return $next($request);
    }
}
