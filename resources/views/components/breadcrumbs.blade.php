{{--
Provides a responsive breadcrumb trail that accepts label and URL pairs, treating items without a URL as the current page.
--}}

@props(['items'])

<nav
    {{ $attributes->class('flex w-full max-w-full justify-start overflow-hidden') }}
    aria-label="Breadcrumb"
    x-data
    x-init="$nextTick(() => {
        const scroller = $refs.scroller;

        if (scroller && scroller.scrollWidth > scroller.clientWidth) {
            $refs.current?.scrollIntoView({ block: 'nearest', inline: 'end' });
        }
    })"
>
    <div
        x-ref="scroller"
        class="w-full max-w-full overflow-x-auto overscroll-x-contain"
    >
        <ol class="inline-flex min-w-max items-center gap-1 text-sm text-gray-600">
        @foreach ($items as $item)
            @php
                $isCurrentPage = blank($item['url'] ?? null);
            @endphp

            <li class="flex min-w-0 items-center gap-1">
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
                        class="shrink-0 rounded-full border border-black/[0.06] bg-gray-50 px-2.5 py-1.5 font-medium text-gray-500 shadow-sm shadow-black/5 transition-colors hover:bg-white hover:text-gray-900"
                    >
                        {{ $item['label'] }}
                    </a>
                @else
                    <span
                        aria-current="page"
                        title="{{ $item['label'] }}"
                        x-ref="current"
                        @class([
                            'block max-w-[14rem] truncate rounded-full border border-black/[0.06] bg-white px-3 py-1.5 font-medium text-gray-950 shadow-sm shadow-black/5 sm:max-w-[30rem] lg:max-w-[40rem]',
                            'ml-2.5' => ! $loop->first,
                        ])
                    >
                        {{ $item['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
        </ol>
    </div>
</nav>
