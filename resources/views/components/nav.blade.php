<nav {{ $attributes->class('flex items-center gap-8 font-normal text-xs') }}>
    <a
        wire:navigate
        href="{{ route('home') }}"
    >
        <div class="grid bg-black place-items-center size-9 md:size-10 rounded-[.65rem] md:rounded-xl">
            <x-heroicon-s-bold class="text-white size-5 md:size-6" />
        </div>
    </a>

    <div class="flex-grow"></div>

    <a
        wire:navigate
        href="{{ route('home') }}"
        @class([
            'transition-colors hover:text-blue-600',
            'text-blue-600' => request()->routeIs('home'),
        ])"
    >
        @if (request()->routeIs('home'))
            <x-heroicon-s-home class="mx-auto size-7" />
        @else
            <x-heroicon-o-home class="mx-auto size-7" />
        @endif

        Home
    </a>

    <a
        wire:navigate
        href="{{ route('posts.index') }}"
        @class([
            'transition-colors hover:text-blue-600',
            'text-blue-600' => request()->routeIs('posts.index'),
        ])"
    >
        @if (request()->routeIs('posts.index'))
            <x-heroicon-s-fire class="mx-auto size-7" />
        @else
            <x-heroicon-o-fire class="mx-auto size-7" />
        @endif

        Latest
    </a>

    <a
        href="{{ route('home') }}#about"
        class="transition-colors hover:text-blue-600"
    >
        <x-heroicon-o-user class="mx-auto size-7" />
        About
    </a>
</nav>
