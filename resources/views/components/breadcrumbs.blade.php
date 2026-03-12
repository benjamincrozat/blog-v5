{{--
Provides a responsive breadcrumb trail that accepts label and URL pairs, treating items without a URL as the current page.
--}}

@props(['items'])

<nav
    {{ $attributes->class('flex w-full max-w-full justify-start overflow-hidden') }}
    aria-label="Breadcrumb"
    x-data
    x-init="$nextTick(() => $refs.current?.scrollIntoView({ block: 'nearest', inline: 'end' }))"
>
    <ol
        x-ref="rail"
        class="flex w-full max-w-full min-w-0 items-center gap-1 overflow-x-auto overscroll-x-contain rounded-full border border-black/[0.06] bg-gray-50 p-1.5 text-sm text-gray-600 shadow-sm shadow-black/5 sm:w-auto"
    >
        @foreach ($items as $item)
            @php
                $isCurrentPage = blank($item['url'] ?? null);
            @endphp

            <li @class([
                'flex min-w-0 items-center gap-1',
                'flex-1 sm:flex-none' => $isCurrentPage,
            ])>
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
                            'block w-full min-w-0 truncate rounded-full bg-white px-3 py-1.5 font-medium text-gray-950 ring-1 ring-black/[0.06] shadow-sm shadow-black/5 sm:w-auto sm:max-w-[30rem] lg:max-w-[40rem]',
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
