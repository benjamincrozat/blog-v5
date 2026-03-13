{{--
Displays the vendor pagination tailwind view.
--}}

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-wrap gap-4 justify-center items-center md:flex-nowrap md:justify-between">
        <div class="text-gray-500">
            Showing
            @if ($paginator->firstItem())
                <span class="font-medium text-gray-700">{{ $paginator->firstItem() }}</span>
                to
                <span class="font-medium text-gray-700">{{ $paginator->lastItem() }}</span>
            @else
                {{ $paginator->count() }}
            @endif
            of
            <span class="font-medium text-gray-700">{{ $paginator->total() }}</span>
            results
        </div>

        <div class="flex flex-wrap gap-1 items-center">
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="grid place-items-center text-gray-300 bg-gray-50 rounded-lg size-8">
                    <span aria-hidden="true">
                        ←
                    </span>
                </span>
            @else
                <a
                    wire:navigate
                    href="{{ $paginator->previousPageUrl() }}"
                    rel="prev"
                    aria-label="{{ __('pagination.previous') }}"
                    class="grid place-items-center bg-gray-50 rounded-lg transition-colors hover:bg-gray-100 size-8"
                >
                    ←
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span aria-disabled="true" class="grid place-items-center text-gray-300 bg-gray-50 rounded-lg size-8">
                        <span>{{ $element }}</span>
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="grid place-items-center text-white bg-gray-900 rounded-lg size-8">
                                <span>{{ $page }}</span>
                            </span>
                        @else
                            <a
                                wire:navigate
                                href="{{ $url }}"
                                aria-label="{{ __('Go to page :page', compact('page')) }}"
                                class="grid place-items-center bg-gray-50 rounded-lg transition-colors hover:bg-gray-100 size-8"
                            >
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a
                    wire:navigate
                    href="{{ $paginator->nextPageUrl() }}"
                    rel="next"
                    aria-label="{{ __('pagination.next') }}"
                    class="grid place-items-center bg-gray-50 rounded-lg transition-colors hover:bg-gray-100 size-8"
                >
                    →
                </a>
            @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="grid place-items-center text-gray-300 bg-gray-50 rounded-lg size-8">
                    <span aria-hidden="true">
                        →
                    </span>
                </span>
            @endif
        </div>
    </nav>
@endif
