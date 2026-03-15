<?php

namespace App\Support;

/**
 * Picks a stable Tailwind background utility for image-less content cards.
 */
class PlaceholderCardColor
{
    /**
     * @var array<int, string>
     */
    protected const COLORS = [
        'bg-amber-600',
        'bg-blue-600',
        'bg-cyan-600',
        'bg-emerald-600',
        'bg-gray-600',
        'bg-green-600',
        'bg-indigo-600',
        'bg-lime-600',
        'bg-pink-600',
        'bg-purple-600',
        'bg-red-600',
        'bg-sky-600',
        'bg-teal-600',
        'bg-yellow-600',
    ];

    public static function for(?string $seed) : string
    {
        $normalizedSeed = trim((string) $seed);

        if ('' === $normalizedSeed) {
            return self::COLORS[0];
        }

        return self::COLORS[abs(crc32($normalizedSeed)) % count(self::COLORS)];
    }
}
