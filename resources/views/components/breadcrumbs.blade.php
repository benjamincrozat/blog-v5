{{--
Presents breadcrumb navigation and accepts label and URL pairs, treating items without a URL as the current page.
--}}

@props(['items'])

<nav
    {{ $attributes->class('w-full overflow-x-auto overscroll-x-contain') }}
    aria-label="Breadcrumb"
    x-data
    x-init="$nextTick(() => {
        if ($el.scrollWidth > $el.clientWidth) {
            $refs.current?.scrollIntoView({ block: 'nearest', inline: 'end' });
        }
    })"
>
    <ol class="inline-flex min-w-max items-center gap-1 rounded-full border border-black/[0.06] bg-gray-50 p-1.5 text-sm shadow-sm shadow-black/5">
        @foreach ($items as $item)
            @php
                $isCurrentPage = blank($item['url'] ?? null);
            @endphp

            <li class="flex items-center gap-1">
                @if (! $loop->first)
                    <x-heroicon-o-chevron-right
                        aria-hidden="true"
                        class="shrink-0 text-gray-300 size-3.5"
                    />
                @endif

                @if (! $isCurrentPage)
                    <a
                        wire:navigate
                        href="{{ $item['url'] }}"
                        class="shrink-0 rounded-full px-2.5 py-1.5 font-medium text-gray-500 transition-colors hover:bg-white hover:text-gray-900"
                    >
                        {{ $item['label'] }}
                    </a>
                @else
                    <span
                        aria-current="page"
                        title="{{ $item['label'] }}"
                        x-ref="current"
                        @class([
                            'block max-w-[14rem] truncate rounded-full bg-white px-3 py-1.5 font-medium text-gray-950 ring-1 ring-black/[0.06] shadow-sm shadow-black/5 sm:max-w-[30rem] lg:max-w-[40rem]',
                            'ml-2.5' => ! $loop->first,
                        ])
                    >
                        {{ $item['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
