<?php

namespace App\Enums;

enum JobSeniority : string
{
    case Intern = 'intern';
    case Junior = 'junior';
    case MidLevel = 'mid-level';
    case Senior = 'senior';
    case Lead = 'lead';
    case Principal = 'principal';
    case Executive = 'executive';

    public function label() : string
    {
        return match ($this) {
            self::Intern => 'Intern',
            self::Junior => 'Junior',
            self::MidLevel => 'Mid-level',
            self::Senior => 'Senior',
            self::Lead => 'Lead',
            self::Principal => 'Principal',
            self::Executive => 'Executive',
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
