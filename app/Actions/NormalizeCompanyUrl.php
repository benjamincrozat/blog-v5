<?php

namespace App\Actions;

use Throwable;

class NormalizeCompanyUrl
{
    public function handle(?string $url) : ?string
    {
        if (! $url) {
            return null;
        }

        try {
            $normalized = trim(strtolower($url));
            $parts = parse_url($normalized);
            $host = $parts['host'] ?? null;

            if (! $host) {
                return null;
            }

            if (str_starts_with($host, 'www.')) {
                $host = substr($host, 4);
            }

            $path = $parts['path'] ?? '';
            $path = rtrim($path, '/');
            $scheme = $parts['scheme'] ?? 'https';

            return $scheme . '://' . $host . ($path ? '/' . ltrim($path, '/') : '');
        } catch (Throwable $e) {
            return null;
        }
    }
}
