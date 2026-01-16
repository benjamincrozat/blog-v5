<?php

namespace App\Actions;

/**
 * Picks a proxy host string for scraping.
 *
 * Extracted to keep proxy selection consistent across jobs/actions.
 * Callers can rely on a hostname:port string being returned.
 */
class SelectProxy
{
    public function select(?string $country = null) : string
    {
        if ($country) {
            $proxy = config('proxies')[$country];

            $port = collect(range($proxy['port_range'], $proxy['port_range'] + 100))->random();

            return "{$proxy['hostname']}:$port";
        }

        // If no country is provided, use the global proxy with a random port.

        $port = collect(range(10000, 10100))->random();

        return "gate.smartproxy.com:$port";
    }
}
