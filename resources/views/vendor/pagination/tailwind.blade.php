@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-wrap items-center justify-center gap-4 md:flex-nowrap md:justify-between">
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

        <div class="flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="grid text-gray-300 bg-gray-100 rounded-lg size-8 place-items-center">
                    <span aria-hidden="true">
                        ←
                    </span>
                </span>
            @else
                <a wire:navigate href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}" class="grid transition-colors rounded-lg bg-blue-50 text-blue-950 hover:bg-blue-50/50 size-8 place-items-center">
                    ←
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span aria-disabled="true" class="grid text-gray-300 bg-gray-100 rounded-lg size-8 place-items-center">
                        <span>{{ $element }}</span>
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="grid text-white bg-gray-900 rounded-lg size-8 place-items-center">
                                <span>{{ $page }}</span>
                            </span>
                        @else
                            <a wire:navigate href="{{ $url }}" aria-label="{{ __('Go to page :page', compact('page')) }}" class="grid transition-colors rounded-lg bg-blue-50 text-blue-950 hover:bg-blue-50/50 size-8 place-items-center">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a wire:navigate href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}" class="grid transition-colors rounded-lg bg-blue-50 text-blue-950 hover:bg-blue-50/50 size-8 place-items-center">
                    →
                </a>
            @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="grid text-gray-300 bg-gray-100 rounded-lg size-8 place-items-center">
                    <span aria-hidden="true">
                        →
                    </span>
                </span>
            @endif
        </div>
    </nav>
@endif
