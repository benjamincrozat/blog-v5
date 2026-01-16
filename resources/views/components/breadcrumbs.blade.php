@props(['items'])

<nav {{ $attributes->class('text-sm text-gray-500') }} aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-2">
        @foreach ($items as $item)
            <li class="flex items-center gap-2">
                @if (! $loop->first)
                    <span aria-hidden="true" class="text-gray-300">/</span>
                @endif

                @if (! empty($item['url']) && ! $loop->last)
                    <a
                        wire:navigate
                        href="{{ $item['url'] }}"
                        class="underline decoration-gray-300/70 underline-offset-4 hover:text-gray-700"
                    >
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="text-gray-600">
                        {{ $item['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
