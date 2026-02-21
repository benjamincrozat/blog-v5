{{--
Renders the components table of contents items view.
--}}

@props(['items'])

<ul {{ $attributes->class('ml-4 grid gap-1') }}>
    @foreach ($items as $item)
        <li>
            <a
                href="#{{ $item['slug'] }}"
                class="font-medium group"
                data-pirsch-event="Clicked table of contents item"
                data-pirsch-meta-value="{!! $item['text'] !!}"
            >
                <span class="opacity-50 group-hover:text-blue-600">→</span>
                <span class="ml-1 underline transition-colors group-hover:text-blue-600 underline-offset-4 decoration-1 decoration-black/30 group-hover:decoration-blue-600/50">{!! $item['text'] !!}</span>
            </a>

            @if (! empty($item['children']))
                <x-table-of-contents.items :items="$item['children']" class="mt-1" />
            @endif
        </li>
    @endforeach
</ul>
