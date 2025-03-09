<div {{ $attributes->except('href') }}>
    <div class="flex items-center gap-2">
        <x-heroicon-o-chevron-right class="inline-block opacity-50 size-3" />

        @if ($attributes->has('href'))
            <a wire:navigate href="{{ $attributes->get('href') }}" class="font-medium underline underline-offset-4 decoration-gray-600/30">
                {{ $slot }}
            </a>
        @else
            {{ $slot }}
        @endif
    </div>
</div>
