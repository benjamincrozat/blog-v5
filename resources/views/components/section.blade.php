@props([
    'title' => null,
    'bigTitle' => false,
    'headingTag' => 'h2',
])

<section {{ $attributes->class('container scroll-mt-4') }}>
    @if (! empty($title))
        <x-typography.heading :big="$bigTitle" :tag="$headingTag" class="mb-8">
            {!! $title !!}
        </x-typography.heading>
    @endif

    {{ $slot }}
</section>
