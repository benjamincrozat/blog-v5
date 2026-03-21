{{--
Accepts: $tool, a published App\Models\Tool instance with optional review and image metadata.
Adapts each database-backed tool to the older horizontal recommendation card presentation used on the tools page.
--}}

@props([
    'tool',
])

@php
    $presets = [
        'cloudways-php' => [
            'headline' => 'Easily deploy PHP web apps',
            'subheadline' => 'PHP 8, scalability, Cloudflare, caching, 24/7 support, and more with Cloudways.',
            'cta' => 'Start free',
            'cta_color' => 'bg-[#3641C2]!',
        ],
        'digitalocean' => [
            'headline' => 'Host your web apps on a VPS',
            'subheadline' => 'DigitalOcean provides affordable, scalable, and reliable VPS hosting.',
            'cta' => 'Start with $200 free credit',
        ],
        'fathom-analytics' => [
            'headline' => 'Know who visits your site',
            'subheadline' => 'Fathom Analytics is a simple, privacy-focused web analytics. No cookies, ads, or tracking.',
            'cta' => 'Start free + $10 off',
            'cta_color' => 'bg-[#171B18]!',
        ],
        'mailcoach' => [
            'headline' => 'Send emails to your users',
            'subheadline' => 'Self-hosted email marketing built for Laravel developers, by Laravel developers.',
            'cta' => 'Start free',
            'cta_color' => 'bg-[#142C6E]!',
        ],
        'tinkerwell' => [
            'headline' => 'Prototype and debug on the fly',
            'subheadline' => 'Tinkerwell lets you code and debug your PHP, Laravel, Symfony, WordPress, etc., apps in an editor designed for fast feedback and quick iterations.',
            'cta' => 'Get started',
            'cta_color' => 'bg-[#4470D4]!',
        ],
        'tower' => [
            'headline' => 'Unlock the power of Git',
            'subheadline' => 'Tower is an easy-to-use and powerful Git client for Mac and Windows.',
            'cta' => 'Start free',
            'cta_color' => 'bg-[#FDCB18]!',
            'cta_text_color' => 'text-yellow-950!',
        ],
        'uptimia' => [
            'headline' => 'Get alerts when your site is down',
            'subheadline' => 'Uptimia monitors your site’s uptime, speed, and SSL from 170+ global checkpoints.',
            'cta' => 'Start free',
            'cta_color' => 'bg-[#009950]!',
        ],
        'wincher' => [
            'headline' => 'Rank higher on Google',
            'subheadline' => 'Use Wincher to track and grow your business’s search visibility. **Use WELCOME30 for 30% off your first invoice.**',
            'cta' => 'Start free',
            'cta_color' => 'bg-[#F09B4F]!',
        ],
        'remodex' => [
            'cta' => 'View on GitHub',
        ],
        'laravel-mcp' => [
            'cta' => 'Read docs',
        ],
    ];

    $preset = $presets[$tool->slug] ?? [];

    $headline = $preset['headline'] ?? $tool->name;

    $subheadline = $preset['subheadline'] ?? (
        filled($tool->content)
        ? $tool->content
        : $tool->description
    );

    $cta = $preset['cta'] ?? match (true) {
        str_contains($tool->outbound_url, 'github.com') => 'View on GitHub',
        str_contains($tool->outbound_url, 'laravel.com') => 'Read docs',
        $tool->has_free_plan || $tool->has_free_trial => 'Start free',
        default => 'Visit website',
    };
@endphp

<x-tools.item
    href="{{ route('merchants.show', $tool->slug) }}"
    name="{{ $tool->name }}"
    headline="{{ $headline }}"
    :subheadline="$subheadline"
    :cta="$cta"
    :cta-color="$preset['cta_color'] ?? null"
    :cta-text-color="$preset['cta_text_color'] ?? null"
    :src="$tool->image_url"
    rel="sponsored noopener"
/>
