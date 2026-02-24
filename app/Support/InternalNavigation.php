<?php

namespace App\Support;

/**
 * Determines whether a link should use Livewire navigation.
 */
class InternalNavigation
{
    public static function shouldUseWireNavigate(?string $href) : bool
    {
        if (blank($href)) {
            return false;
        }

        if (str_starts_with($href, '#')) {
            return false;
        }

        if (str_starts_with($href, 'mailto:') || str_starts_with($href, 'tel:')) {
            return false;
        }

        $scheme = parse_url($href, PHP_URL_SCHEME);

        if (null === $scheme) {
            return true;
        }

        if (! in_array($scheme, ['http', 'https'], true)) {
            return false;
        }

        $hrefHost = parse_url($href, PHP_URL_HOST);
        $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);

        return filled($hrefHost) && filled($appHost) && $hrefHost === $appHost;
    }
}
