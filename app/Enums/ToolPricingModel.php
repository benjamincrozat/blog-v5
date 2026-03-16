<?php

namespace App\Enums;

/**
 * Defines the supported tool pricing models.
 */
enum ToolPricingModel : string
{
    case Free = 'free';
    case Freemium = 'freemium';
    case Paid = 'paid';

    public function label() : string
    {
        return match ($this) {
            self::Free => 'Free',
            self::Freemium => 'Freemium',
            self::Paid => 'Paid',
        };
    }

    /**
     * @return array<int, string>
     */
    public static function values() : array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
