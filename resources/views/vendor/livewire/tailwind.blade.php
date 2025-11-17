@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav
            role="navigation"
            aria-label="Pagination Navigation"
            class="flex flex-wrap gap-4 justify-center items-center md:flex-nowrap md:justify-between"
        >
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
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span
                        aria-disabled="true"
                        aria-label="{{ __('pagination.previous') }}"
                        class="grid place-items-center text-gray-300 bg-gray-50 rounded-lg size-8"
                    >
                        <span aria-hidden="true">
                            ←
                        </span>
                    </span>
                @else
                    <button
                        type="button"
                        wire:click="previousPage('{{ $paginator->getPageName() }}')"
                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                        wire:loading.attr="disabled"
                        dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                        aria-label="{{ __('pagination.previous') }}"
                        data-pirsch-event="Clicked pagination previous"
                        class="grid place-items-center bg-gray-50 rounded-lg transition-colors hover:bg-gray-100 size-8"
                    >
                        ←
                    </button>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span
                            aria-disabled="true"
                            class="grid place-items-center text-gray-300 bg-gray-50 rounded-lg size-8"
                        >
                            <span>{{ $element }}</span>
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                @if ($page == $paginator->currentPage())
                                    <span
                                        aria-current="page"
                                        class="grid place-items-center text-white bg-gray-900 rounded-lg size-8"
                                    >
                                        <span>{{ $page }}</span>
                                    </span>
                                @else
                                    <button
                                        type="button"
                                        wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                        wire:loading.attr="disabled"
                                        aria-label="Go to page {{ $page }}"
                                        data-pirsch-event="Clicked pagination page"
                                        data-pirsch-meta-page="{{ $page }}"
                                        class="grid place-items-center bg-gray-50 rounded-lg transition-colors hover:bg-gray-100 size-8"
                                    >
                                        {{ $page }}
                                    </button>
                                @endif
                            </span>
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <button
                        type="button"
                        wire:click="nextPage('{{ $paginator->getPageName() }}')"
                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                        wire:loading.attr="disabled"
                        dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                        aria-label="{{ __('pagination.next') }}"
                        data-pirsch-event="Clicked pagination next"
                        class="grid place-items-center bg-gray-50 rounded-lg transition-colors hover:bg-gray-100 size-8"
                    >
                        →
                    </button>
                @else
                    <span
                        aria-disabled="true"
                        aria-label="{{ __('pagination.next') }}"
                        class="grid place-items-center text-gray-300 bg-gray-50 rounded-lg size-8"
                    >
                        <span aria-hidden="true">
                            →
                        </span>
                    </span>
                @endif
            </div>
        </nav>
    @endif
</div>
