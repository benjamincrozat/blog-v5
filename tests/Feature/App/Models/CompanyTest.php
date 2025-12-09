<?php

use App\Models\Company;
use App\Actions\NormalizeCompanyUrl;
use App\Actions\NormalizeCompanyDomain;

it('normalizes domain and url when saving', function () {
    /** @var NormalizeCompanyUrl $urlNormalizer */
    $urlNormalizer = app(NormalizeCompanyUrl::class);
    /** @var NormalizeCompanyDomain $domainNormalizer */
    $domainNormalizer = app(NormalizeCompanyDomain::class);
    $normalizedUrl = $urlNormalizer->handle('HTTPS://WWW.Example.COM/careers/?utm_source=newsletter');
    $normalizedDomain = $domainNormalizer->handle($normalizedUrl);

    $company = Company::factory()->create([
        'name' => 'Example Co',
        'url' => 'HTTPS://WWW.Example.COM/careers/?utm_source=newsletter',
    ]);

    expect($company->domain)->toBe('example.com')
        ->and($company->url)->toBe('https://example.com/careers')
        ->and($company->slug)->toBe('example-co');
});
