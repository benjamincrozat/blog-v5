@props([
    'tag' => 'h1',
])

<{{ $tag }} {{ $attributes->class('font-medium tracking-tight text-center text-black text-[2rem]/none sm:text-4xl/none md:text-5xl lg:text-7xl text-balance') }}>
    {{ $slot }}
</{{ $tag }}>