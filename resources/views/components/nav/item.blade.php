@props([
    'icon' => null,
    'activeIcon' => null,
])

<{{ $attributes->has('href') ? 'a' : 'button' }}
    {{ $attributes->class([
        'transition-colors hover:text-blue-600',
        'text-blue-600' => request()->fullUrlIs($attributes->get('href')),
    ])->merge([
        'wire:navigate' => ! $attributes->has('no-wire-navigate') && $attributes->has('href'),
        'data-pirsch-event' => "Clicked navigation item",
        'data-pirsch-meta-value' => strip_tags($slot),
    ]) }}
>
    @if (! empty($activeIcon) && request()->fullUrlIs($attributes->get('href')))
        <x-dynamic-component :component="$activeIcon" class="mx-auto size-6 md:size-7" />
    @elseif (! empty($icon))
        <x-dynamic-component :component="$icon" class="mx-auto size-6 md:size-7" />
    @endif

    {{ $slot }}
</{{ $attributes->has('href') ? 'a' : 'button' }}>
