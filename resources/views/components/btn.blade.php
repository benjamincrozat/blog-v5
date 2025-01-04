@if ($attributes->has('href'))
<a
@else
<button
@endif
    {{
        $attributes
            ->class([
                'font-medium rounded-xl transition-colors',
                'bg-gray-200 hover:bg-gray-100' => ! $attributes->has('primary'),
                'bg-blue-600 hover:bg-blue-500 text-white' => $attributes->has('primary'),
                'px-4 py-3' => ! $attributes->has('size'),
                'px-5 py-3 text-lg' => 'md' === $attributes->get('size'),
            ])
            ->merge([
                'href' => route('posts.index'),
            ])
    }}
>
    {{ $slot }}
@if ($attributes->has('href'))
</a>
@else
</button>
@endif
