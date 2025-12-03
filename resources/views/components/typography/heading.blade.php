@props([
    'tag' => 'h1',
    'big' => false,
])

<{{ $tag }}
    {{ $attributes->class([
        'font-bold tracking-widest text-center text-black uppercase text-balance',
        'text-xl/tight md:text-2xl/tight' => $attributes->has('big'),
    ]) }}
>
    {{ $slot }}
</{{ $tag }}>
