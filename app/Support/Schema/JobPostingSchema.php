<?php

namespace App\Support\Schema;

use App\Models\Job;

use function collect;

use Illuminate\Support\Str;

class JobPostingSchema
{
    public static function fromJob(Job $job) : array
    {
        $locations = collect($job->locations ?? [])
            ->filter()
            ->values()
            ->all();

        $isRemote = self::isRemote($job, $locations);

        $jobLocations = self::buildJobLocations($locations, $isRemote);
        $applicantLocationRequirements = self::buildApplicantLocationRequirements($locations, $isRemote);

        $validThrough = optional($job->created_at)
            ?->copy()
            ->addDays(30)
            ->toIso8601String();

        $schema = [
            '@context' => 'https://schema.org/',
            '@type' => 'JobPosting',
            'title' => $job->title,
            'description' => $job->description,
            'identifier' => [
                '@type' => 'PropertyValue',
                'name' => $job->company->name,
                'value' => (string) $job->id,
            ],
            'datePosted' => optional($job->created_at)?->toIso8601String(),
            'validThrough' => $validThrough,
            'employmentType' => 'FULL_TIME',
            'hiringOrganization' => [
                '@type' => 'Organization',
                'name' => $job->company->name,
                'sameAs' => $job->company->url,
                'logo' => $job->company->logo,
            ],
            'jobLocationType' => $isRemote ? 'TELECOMMUTE' : null,
            'jobLocation' => $jobLocations,
            'applicantLocationRequirements' => $applicantLocationRequirements,
            'baseSalary' => [
                '@type' => 'MonetaryAmount',
                'currency' => $job->currency ?? 'USD',
                'value' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => $job->min_salary,
                    'maxValue' => $job->max_salary,
                    'unitText' => 'YEAR',
                ],
            ],
            'directApply' => false,
        ];

        return array_filter(
            $schema,
            fn ($value) => null !== $value && ([] !== $value || is_bool($value))
        );
    }

    /**
     * @param  array<int, string>  $locations
     * @return array<int, array<string, mixed>>|array<string, mixed>
     */
    private static function buildJobLocations(array $locations, bool $isRemote) : array
    {
        if ([] === $locations) {
            if ($isRemote) {
                return self::remotePlace();
            }

            return [];
        }

        $places = collect($locations)
            ->filter()
            ->map(fn (string $location) => self::buildPlaceFromLocation($location, $isRemote))
            ->filter()
            ->values();

        if ($places->isEmpty() && $isRemote) {
            return self::remotePlace();
        }

        return 1 === $places->count()
            ? $places->first()
            : $places->all();
    }

    /**
     * @param  array<int, string>  $locations
     * @return array<int, array<string, string>>|array<string, string>
     */
    private static function buildApplicantLocationRequirements(array $locations, bool $isRemote) : array
    {
        $countries = collect($locations)
            ->map(fn (string $location) => self::extractCountry($location))
            ->filter()
            ->unique()
            ->values();

        if ($countries->isEmpty()) {
            if ($isRemote) {
                return self::worldwideApplicantRequirement();
            }

            return [];
        }

        if (1 === $countries->count()) {
            return self::countryApplicantRequirement($countries->first());
        }

        return $countries
            ->map(fn (string $country) => self::countryApplicantRequirement($country))
            ->all();
    }

    private static function buildPlaceFromLocation(string $location, bool $isRemote) : ?array
    {
        [$country, $locality, $region] = self::extractLocationParts($location);

        if (null === $country && null === $locality && null === $region) {
            if ($isRemote || self::containsRemoteKeyword($location)) {
                return self::remotePlace($location);
            }

            return null;
        }

        $address = [
            '@type' => 'PostalAddress',
        ];

        if (null !== $locality) {
            $address['addressLocality'] = $locality;
        }

        if (null !== $region) {
            $address['addressRegion'] = $region;
        }

        $address['addressCountry'] = $country ?? 'Worldwide';

        return [
            '@type' => 'Place',
            'name' => '' !== trim($location) ? trim($location) : ($locality ?? $country ?? 'Remote'),
            'address' => $address,
        ];
    }

    /**
     * @return array{0: string|null, 1: string|null, 2: string|null}
     */
    private static function extractLocationParts(string $location) : array
    {
        $segments = array_values(
            array_filter(
                array_map(
                    fn (string $segment) => self::sanitizeSegment($segment),
                    explode(',', $location)
                ),
                fn (string $segment) => '' !== $segment
            )
        );

        if ([] === $segments) {
            return [null, null, null];
        }

        $country = array_pop($segments);
        $locality = [] !== $segments ? array_shift($segments) : null;
        $region = [] !== $segments ? implode(', ', $segments) : null;

        return [$country, $locality, $region];
    }

    private static function extractCountry(string $location) : ?string
    {
        [$country] = self::extractLocationParts($location);

        return $country;
    }

    private static function sanitizeSegment(string $segment) : string
    {
        $sanitized = trim($segment);

        $sanitized = preg_replace_callback(
            '/\(([^)]*)\)/',
            fn (array $matches) => self::containsRemoteKeyword($matches[1]) ? '' : $matches[0],
            $sanitized
        );

        foreach (self::remoteKeywords() as $keyword) {
            $sanitized = preg_replace('/\b' . preg_quote($keyword, '/') . '\b/i', '', $sanitized);
        }

        $sanitized = preg_replace('/[-–—]/', ' ', $sanitized);

        $sanitized = preg_replace('/\s{2,}/', ' ', (string) $sanitized);

        return trim((string) $sanitized, " \t\n\r\0\x0B,.-");
    }

    private static function remotePlace(?string $label = null) : array
    {
        return [
            '@type' => 'Place',
            'name' => self::resolveRemoteLabel($label),
            'address' => [
                '@type' => 'PostalAddress',
                'addressCountry' => 'Worldwide',
            ],
        ];
    }

    private static function resolveRemoteLabel(?string $label) : string
    {
        $resolved = trim((string) $label);

        return '' !== $resolved ? $resolved : 'Remote';
    }

    private static function isRemote(Job $job, array $locations) : bool
    {
        $setting = trim((string) $job->setting);

        if ('' !== $setting) {
            return self::containsRemoteKeyword($setting);
        }

        return collect($locations)->contains(fn (string $location) => self::containsRemoteKeyword($location));
    }

    private static function containsRemoteKeyword(string $value) : bool
    {
        $haystack = Str::of($value)->lower();

        foreach (self::remoteKeywords() as $keyword) {
            if ($haystack->contains($keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<int, string>
     */
    private static function remoteKeywords() : array
    {
        return [
            'remote',
            'telecommute',
            'distributed',
            'anywhere',
            'worldwide',
            'global',
        ];
    }

    private static function worldwideApplicantRequirement() : array
    {
        return [
            '@type' => 'Country',
            'name' => 'Worldwide',
        ];
    }

    private static function countryApplicantRequirement(string $country) : array
    {
        return [
            '@type' => 'Country',
            'name' => $country,
        ];
    }
}
