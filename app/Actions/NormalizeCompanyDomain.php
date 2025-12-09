<?php

namespace App\Actions;

use Throwable;

class NormalizeCompanyDomain
{
    public function handle(?string $url) : ?string
    {
        if (! $url) {
            return null;
        }

        try {
            $parts = parse_url($url);
            $host = $parts['host'] ?? null;

            if ($host) {
                $host = strtolower($host);

                if (str_starts_with($host, 'www.')) {
                    $host = substr($host, 4);
                }
            }

            return $host ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }
}
