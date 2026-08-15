<?php

namespace App\Support;

/**
 * Picks a stable Tailwind background for a content card without an image.
 *
 * The same non-empty text always maps to the same color in the fixed build-safe
 * list. Empty text uses the first color. Cards stay visually stable without
 * saving a color in the database.
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
