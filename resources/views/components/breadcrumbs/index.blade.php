<div {{ $attributes->class('flex items-center gap-2') }}>
    <a wire:navigate href="{{ route('home') }}" class="font-medium underline underline-offset-4 decoration-gray-600/30">
        /
    </a>

    {{ $slot }}
</div>
