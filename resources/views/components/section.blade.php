@props([
    'title' => null,
    'bigTitle' => false,
])

<section {{ $attributes->class('container scroll-mt-4') }}>
    @if (! empty($title))
        <x-typography.heading :big="$bigTitle" class="mb-8">
            {!! $title !!}
        </x-typography.heading>
    @endif

    {{ $slot }}
</section>
