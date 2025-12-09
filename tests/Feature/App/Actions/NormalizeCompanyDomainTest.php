<?php

use App\Actions\NormalizeCompanyDomain;

it('extracts domain and strips www', function () {
    $domain = app(NormalizeCompanyDomain::class)->handle('https://www.Example.org/path');

    expect($domain)->toBe('example.org');
});

it('returns null when host is missing', function () {
    $domain = app(NormalizeCompanyDomain::class)->handle('/relative/path');

    expect($domain)->toBeNull();
});
