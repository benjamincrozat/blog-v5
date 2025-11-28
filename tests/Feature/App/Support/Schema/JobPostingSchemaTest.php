<?php

use App\Models\Job;
use App\Models\Company;
use App\Enums\JobSetting;
use App\Enums\EmploymentStatus;
use App\Support\Schema\JobPostingSchema;

dataset('employment_status_mappings', [
    'full-time' => [EmploymentStatus::FullTime->value, 'FULL_TIME'],
    'part-time' => [EmploymentStatus::PartTime->value, 'PART_TIME'],
    'contract' => [EmploymentStatus::Contract->value, 'CONTRACTOR'],
    'temporary' => [EmploymentStatus::Temporary->value, 'TEMPORARY'],
    'internship' => [EmploymentStatus::Internship->value, 'INTERN'],
    'freelance' => [EmploymentStatus::Freelance->value, 'CONTRACTOR'],
    'other' => [EmploymentStatus::Other->value, 'OTHER'],
    'default' => ['custom-status', null],
]);

it('builds schema for fully remote jobs without explicit locations', function () {
    $createdAt = now()->startOfDay();

    $company = Company::factory()->make([
        'name' => 'Remote SRL',
        'url' => 'https://remote.example.com',
        'logo' => 'https://remote.example.com/logo.png',
    ]);

    $job = Job::factory()->make([
        'id' => 101,
        'title' => 'Staff Platform Engineer',
        'description' => 'Build distributed systems.',
        'employment_status' => EmploymentStatus::FullTime->value,
        'setting' => JobSetting::FullyRemote->value,
        'locations' => [],
        'currency' => 'EUR',
        'min_salary' => 120000,
        'max_salary' => 180000,
        'created_at' => $createdAt,
    ]);

    $job->setRelation('company', $company);

    $schema = JobPostingSchema::fromJob($job);

    expect($schema)->toMatchArray([
        '@context' => 'https://schema.org/',
        '@type' => 'JobPosting',
        'title' => 'Staff Platform Engineer',
        'description' => 'Build distributed systems.',
        'identifier' => [
            '@type' => 'PropertyValue',
            'name' => 'Remote SRL',
            'value' => '101',
        ],
        'datePosted' => $createdAt->toIso8601String(),
        'validThrough' => $createdAt->copy()->addDays(30)->toIso8601String(),
        'employmentType' => 'FULL_TIME',
        'hiringOrganization' => [
            '@type' => 'Organization',
            'name' => 'Remote SRL',
            'sameAs' => 'https://remote.example.com',
            'logo' => 'https://remote.example.com/logo.png',
        ],
        'jobLocationType' => 'TELECOMMUTE',
        'jobLocation' => [
            '@type' => 'Place',
            'name' => 'Remote',
            'address' => [
                '@type' => 'PostalAddress',
                'addressCountry' => 'Worldwide',
            ],
        ],
        'applicantLocationRequirements' => [
            '@type' => 'Country',
            'name' => 'Worldwide',
        ],
        'baseSalary' => [
            '@type' => 'MonetaryAmount',
            'currency' => 'EUR',
            'value' => [
                '@type' => 'QuantitativeValue',
                'minValue' => 120000,
                'maxValue' => 180000,
                'unitText' => 'YEAR',
            ],
        ],
        'directApply' => false,
    ]);
});

it('builds schema for jobs with mixed applicant locations', function () {
    $company = Company::factory()->make([
        'name' => 'Global Corp',
        'url' => 'https://global.example.com',
        'logo' => 'https://global.example.com/logo.png',
    ]);

    $job = Job::factory()->make([
        'id' => 202,
        'title' => 'Product Designer',
        'description' => 'Design thoughtful experiences.',
        'employment_status' => EmploymentStatus::Contract->value,
        'setting' => JobSetting::Hybrid->value,
        'locations' => [
            'Paris, Île-de-France, France',
            'Irvine, Orange County, CA, United States',
            'France',
            ' , , ',
        ],
        'currency' => 'CAD',
        'min_salary' => 70000,
        'max_salary' => 90000,
        'created_at' => now(),
    ]);

    $job->setRelation('company', $company);

    $schema = JobPostingSchema::fromJob($job);

    expect($schema['jobLocation'])->toHaveCount(4);

    expect($schema['jobLocation'][0])->toMatchArray([
        '@type' => 'Place',
        'name' => 'Paris, Île-de-France, France',
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => 'Paris',
            'addressRegion' => 'Île-de-France',
            'addressCountry' => 'France',
        ],
    ]);

    expect($schema['jobLocation'][1]['address'])->toMatchArray([
        '@type' => 'PostalAddress',
        'addressLocality' => 'Irvine',
        'addressRegion' => 'Orange County, CA',
        'addressCountry' => 'United States',
    ]);

    expect($schema['jobLocation'][2]['address'])->toMatchArray([
        '@type' => 'PostalAddress',
        'addressCountry' => 'France',
    ]);

    expect($schema['jobLocation'][3]['address']['addressCountry'])->toBe('Worldwide');

    expect($schema['applicantLocationRequirements'])->toBe([
        [
            '@type' => 'Country',
            'name' => 'France',
        ],
        [
            '@type' => 'Country',
            'name' => 'United States',
        ],
    ]);

    expect($schema['employmentType'])->toBe('CONTRACTOR');

    expect(array_key_exists('jobLocationType', $schema))->toBeFalse();
});

it('omits nullable sections and defaults currency when details are missing', function () {
    $company = Company::factory()->make([
        'name' => 'Operations Inc.',
        'url' => 'https://operations.example.com',
        'logo' => 'https://operations.example.com/logo.png',
    ]);

    $job = Job::factory()->make([
        'id' => 303,
        'title' => 'Operations Lead',
        'description' => 'Keep the team aligned.',
        'employment_status' => null,
        'setting' => JobSetting::OnSite->value,
        'locations' => null,
        'currency' => null,
        'min_salary' => 50000,
        'max_salary' => 80000,
        'created_at' => now(),
    ]);

    $job->setRelation('company', $company);

    $schema = JobPostingSchema::fromJob($job);

    expect($schema['baseSalary']['currency'])->toBe('USD');
    expect(array_key_exists('employmentType', $schema))->toBeFalse();
    expect(array_key_exists('jobLocationType', $schema))->toBeFalse();
    expect(array_key_exists('jobLocation', $schema))->toBeFalse();
    expect(array_key_exists('applicantLocationRequirements', $schema))->toBeFalse();
    expect($schema['directApply'])->toBeFalse();
});

it('maps employment statuses to structured data values', function (?string $status, ?string $expected) {
    $company = Company::factory()->make([
        'name' => 'Status Inc.',
        'url' => 'https://status.example.com',
        'logo' => 'https://status.example.com/logo.png',
    ]);

    $job = Job::factory()->make([
        'id' => 404,
        'title' => 'Generalist',
        'description' => 'Own several workflows.',
        'employment_status' => $status,
        'setting' => JobSetting::OnSite->value,
        'locations' => ['Paris, Île-de-France, France'],
        'currency' => 'USD',
        'min_salary' => 60000,
        'max_salary' => 90000,
        'created_at' => now(),
    ]);

    $job->setRelation('company', $company);

    $schema = JobPostingSchema::fromJob($job);

    if (null === $expected) {
        expect(array_key_exists('employmentType', $schema))->toBeFalse();
    } else {
        expect($schema['employmentType'])->toBe($expected);
    }
})->with('employment_status_mappings');
