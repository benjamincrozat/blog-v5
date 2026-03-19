{{--
Accepts: $tool, a published App\Models\Tool instance with optional review and image metadata.
Adapts each database-backed tool to the older horizontal recommendation card presentation used on the tools page.
--}}

@props([
    'tool',
])

@php
    $ctaColors = [
        'cloudways-php' => 'bg-[#3641C2]!',
        'digitalocean' => null,
        'fathom-analytics' => 'bg-[#171B18]!',
        'mailcoach' => 'bg-[#142C6E]!',
        'tinkerwell' => 'bg-[#4470D4]!',
        'tower' => 'bg-[#FDCB18]!',
        'uptimia' => 'bg-[#009950]!',
        'wincher' => 'bg-[#F09B4F]!',
    ];

    $ctaTextColors = [
        'tower' => 'text-yellow-950!',
    ];

    $subheadline = filled($tool->content)
        ? $tool->content
        : $tool->description;
@endphp

<x-tools.item
    href="{{ route('merchants.show', $tool->slug) }}"
    name="{{ $tool->name }}"
    headline="{{ $tool->name }}"
    :subheadline="$subheadline"
    cta="Visit tool"
    :cta-color="$ctaColors[$tool->slug] ?? null"
    :cta-text-color="$ctaTextColors[$tool->slug] ?? null"
    :src="$tool->image_url"
    rel="sponsored noopener"
/>
