<?php

namespace App\Enums;

enum JobSetting : string
{
    case FullyRemote = 'fully-remote';
    case Hybrid = 'hybrid';
    case OnSite = 'on-site';

    public function label() : string
    {
        return match ($this) {
            self::FullyRemote => 'Fully remote',
            self::Hybrid => 'Hybrid',
            self::OnSite => 'On-site',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options() : array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->all();
    }

    /**
     * @return array<int, string>
     */
    public static function values() : array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
