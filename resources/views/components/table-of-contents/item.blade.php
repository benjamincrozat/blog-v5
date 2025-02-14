@props(['heading'])

<li {{ $attributes->class('line-clamp-1') }}>
    <div class="inline-block font-normal group">
        <span class="mr-[.35rem] transition-colors group-hover:decoration-blue-600/30 group-hover:text-blue-600">
            <x-heroicon-o-arrow-right class="inline -translate-y-px size-3" />
        </span>

        <a href="#{{ $heading['slug'] }}" class="transition-colors group-hover:decoration-blue-600/30 group-hover:text-blue-600">
            {{ $heading['text'] }}
        </a>
    </div>

    @if (! empty($heading['children']))
        <x-table-of-contents :headings="$heading['children']" class="ml-4" />
    @endif
</li>
