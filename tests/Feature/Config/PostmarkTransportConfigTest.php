<?php

use CraigPaul\Mail\PostmarkTransport;

it('resolves the postmark transport when only POSTMARK_API_KEY is configured', function () {
    $originalApiKey = getenv('POSTMARK_API_KEY') ?: null;
    $originalToken = getenv('POSTMARK_TOKEN') ?: null;

    setEnvironmentValue('POSTMARK_API_KEY', 'postmark-api-key');
    setEnvironmentValue('POSTMARK_TOKEN', null);

    try {
        $services = require config_path('services.php');

        config()->set('services.postmark', $services['postmark']);

        $transport = app('mail.manager')->createSymfonyTransport(config('mail.mailers.postmark'));

        expect($transport)->toBeInstanceOf(PostmarkTransport::class);
    } finally {
        setEnvironmentValue('POSTMARK_API_KEY', $originalApiKey);
        setEnvironmentValue('POSTMARK_TOKEN', $originalToken);
    }
});

function setEnvironmentValue(string $key, ?string $value) : void
{
    if (null === $value) {
        putenv($key);
        unset($_ENV[$key], $_SERVER[$key]);

        return;
    }

    putenv("{$key}={$value}");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}
