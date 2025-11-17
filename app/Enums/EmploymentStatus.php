<?php

namespace App\Enums;

enum EmploymentStatus : string
{
    case FullTime = 'full-time';
    case PartTime = 'part-time';
    case Contract = 'contract';
    case Temporary = 'temporary';
    case Internship = 'internship';
    case Freelance = 'freelance';
    case Other = 'other';

    public function label() : string
    {
        return match ($this) {
            self::FullTime => 'Full-time',
            self::PartTime => 'Part-time',
            self::Contract => 'Contract',
            self::Temporary => 'Temporary',
            self::Internship => 'Internship',
            self::Freelance => 'Freelance',
            self::Other => 'Other',
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
