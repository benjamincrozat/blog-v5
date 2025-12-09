<?php

use App\Actions\NormalizeCompanyUrl;

it('normalizes url by lowercasing, stripping www, and trimming trailing slash', function () {
    $normalized = app(NormalizeCompanyUrl::class)->handle('HTTPS://WWW.Example.COM/path/?utm_source=newsletter');

    expect($normalized)->toBe('https://example.com/path');
});

it('returns null when host is missing', function () {
    $normalized = app(NormalizeCompanyUrl::class)->handle('/relative/path');

    expect($normalized)->toBeNull();
});
