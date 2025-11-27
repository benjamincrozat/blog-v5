@props([
    'posts',
    'compact' => false,
])

<ul {{ $attributes->class([
    'grid gap-10 gap-y-16 xl:gap-x-16',
    'md:grid-cols-2 xl:grid-cols-3' => ! $compact,
    'md:grid-cols-2' => $compact,
]) }}>
    @foreach ($posts as $post)
        <li>
            @empty($compact)
                <x-post :$post />
            @else
                <x-compact-post :$post />
            @endempty
        </li>
    @endforeach
</ul>