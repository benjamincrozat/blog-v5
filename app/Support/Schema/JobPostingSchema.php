<?php

namespace App\Support\Schema;

use App\Models\Job;

class JobPostingSchema
{
    public static function fromJob(Job $job) : array
    {
        $locations = collect($job->locations ?? []);

        $jobLocations = self::buildJobLocations($job, $locations->all());
        $applicantLocationRequirements = self::buildApplicantLocationRequirements($job, $locations->all());

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
            'jobLocationType' => 'fully-remote' === $job->setting ? 'TELECOMMUTE' : null,
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
    private static function buildJobLocations(Job $job, array $locations) : array
    {
        if ([] === $locations) {
            if ('fully-remote' === $job->setting) {
                return [
                    '@type' => 'Place',
                    'name' => 'Remote',
                    'address' => [
                        '@type' => 'PostalAddress',
                        'addressCountry' => 'Worldwide',
                    ],
                ];
            }

            return [];
        }

        $places = collect($locations)
            ->filter()
            ->map(fn (string $location) => self::buildPlaceFromLocation($location))
            ->values();

        return 1 === $places->count()
            ? $places->first()
            : $places->all();
    }

    /**
     * @param  array<int, string>  $locations
     * @return array<int, array<string, string>>|array<string, string>
     */
    private static function buildApplicantLocationRequirements(Job $job, array $locations) : array
    {
        $countries = collect($locations)
            ->map(fn (string $location) => self::extractCountry($location))
            ->filter()
            ->unique()
            ->values();

        if ($countries->isEmpty()) {
            if ('fully-remote' === $job->setting) {
                return [
                    '@type' => 'Country',
                    'name' => 'Worldwide',
                ];
            }

            return [];
        }

        if (1 === $countries->count()) {
            return [
                '@type' => 'Country',
                'name' => $countries->first(),
            ];
        }

        return $countries
            ->map(fn (string $country) => [
                '@type' => 'Country',
                'name' => $country,
            ])
            ->all();
    }

    private static function buildPlaceFromLocation(string $location) : array
    {
        [$country, $locality, $region] = self::extractLocationParts($location);

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
            'name' => $location,
            'address' => $address,
        ];
    }

    /**
     * @return array{0: string|null, 1: string|null, 2: string|null}
     */
    private static function extractLocationParts(string $location) : array
    {
        $segments = array_values(array_filter(array_map('trim', explode(',', $location)), fn ($segment) => '' !== $segment));

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
}
