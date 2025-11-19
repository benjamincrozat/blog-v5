<nav {{ $attributes->class('flex flex-wrap md:flex-nowrap justify-center items-center md:justify-between gap-6 md:gap-8 font-normal text-xs') }}>
    <a
        wire:navigate
        href="{{ route('home') }}"
        data-pirsch-event="Clicked the logo"
        class="flex gap-3 items-center text-black transition-colors hover:text-blue-600"
    >
        <div class="relative">
            <x-icon-logo class="h-8 fill-current md:h-9" />

            <div class="grid absolute right-[-.65rem] bottom-[-.65rem] place-items-center bg-white rounded-full size-5">
                <x-heroicon-s-home class="h-[.9rem] text-current" />
            </div>
        </div>

        <span class="text-base font-bold tracking-widest uppercase">
            benjamincrozat.com
        </span>
    </a>

    <div class="flex flex-grow gap-6 justify-around items-center md:justify-end md:gap-8">
        <x-nav.item
            active-icon="heroicon-s-fire"
            icon="heroicon-o-fire"
            href="{{ route('posts.index') }}"
        >
            Latest
        </x-nav.item>

        <x-nav.item
            active-icon="heroicon-s-link"
            icon="heroicon-o-link"
            href="{{ route('links.index') }}"
        >
            Links
        </x-nav.item>

        <x-nav.item
            active-icon="heroicon-s-briefcase"
            icon="heroicon-o-briefcase"
            href="{{ route('jobs.index') }}"
            class="relative"
        >
            Jobs

            <span @class([
                'absolute top-[-.35rem] right-[-.35rem] size-[1.15rem] rounded-full text-white text-[.6rem] grid place-items-center',
                'bg-blue-950' => request()->routeIs('jobs.*'),
                'bg-blue-600' => ! request()->routeIs('jobs.*'),
            ])">
                {{ $recentJobsCount }}
            </span>
        </x-nav.item>

        <x-nav.item
            active-icon="heroicon-s-computer-desktop"
            icon="heroicon-o-computer-desktop"
            href="{{ route('tools.index') }}"
        >
            Tools
        </x-nav.item>

        @auth
            <x-dropdown>
                <x-slot:btn>
                    <img
                        src="{{ auth()->user()->avatar }}"
                        alt="{{ auth()->user()->name }}'s GitHub avatar"
                        class="mx-auto rounded-full size-6 md:size-7"
                    />

                    Account
                </x-slot>

                <x-slot:items>
                    <div class="px-4 py-2">
                        {{ auth()->user()->name }}
                    </div>

                    <x-dropdown.divider />

                    @if (auth()->user()->isAdmin())
                        <x-dropdown.item
                            icon="heroicon-o-adjustments-horizontal"
                            href="{{ route('filament.admin.pages.dashboard') }}"
                        >
                            Admin
                        </x-dropdown.item>

                        <x-dropdown.item
                            icon="icon-horizon"
                            href="{{ route('horizon.index') }}"
                        >
                            Horizon
                        </x-dropdown.item>
                    @endif

                    <x-dropdown.divider />

                    <x-dropdown.item
                        icon="heroicon-o-chat-bubble-oval-left"
                        wire:navigate
                        href="{{ route('user.comments') }}"
                    >
                        Your comments
                    </x-dropdown.item>

                    <x-dropdown.item
                        icon="heroicon-o-link"
                        wire:navigate
                        href="{{ route('user.links') }}"
                    >
                        Your links
                    </x-dropdown.item>

                    <x-dropdown.divider />

                    <x-dropdown.item
                        icon="heroicon-o-arrow-right-end-on-rectangle"
                        destructive
                        form="logout-form"
                    >
                        Log out
                    </x-dropdown.item>

                    <form method="POST" action="{{ route('logout') }}" id="logout-form" class="hidden">
                        @csrf
                    </form>
                </x-slot>
            </x-dropdown>
        @else
            <x-nav.item
                no-wire-navigate
                href="{{ route('auth.redirect') }}"
                icon="iconoir-github"
            >
                Sign in
            </x-nav.item>
        @endauth

        <x-dropdown>
            <x-slot:btn>
                <x-heroicon-o-ellipsis-horizontal
                    class="mx-auto transition-transform size-6 md:size-7"
                    x-bind:class="{ 'rotate-90': open }"
                />
                More
            </x-slot>

            <x-slot:items>
                <x-dropdown.divider>
                    More
                </x-dropdown.divider>

                <x-dropdown.item
                    icon="heroicon-o-tag"
                    wire:navigate
                    href="{{ route('categories.index') }}"
                >
                    Categories
                </x-dropdown.item>

                <x-dropdown.item
                    icon="heroicon-o-magnifying-glass"
                    @click="$dispatch('search'); open = false"
                >
                    Search
                </x-dropdown.item>

                <x-dropdown.item
                    icon="heroicon-o-megaphone"
                    description="Show off your business through display ads or sponsored articles."
                    wire:navigate
                    href="{{ route('advertise') }}"
                >
                    Advertise
                </x-dropdown.item>

                <x-dropdown.item
                    icon="heroicon-o-question-mark-circle"
                    href="{{ route('home') }}#about"
                >
                    About me
                </x-dropdown.item>

                <x-dropdown.item
                    icon="heroicon-o-envelope"
                    href="mailto:hello@benjamincrozat.com"
                >
                    Contact me
                </x-dropdown.item>

                <x-dropdown.divider>
                    Freebies
                </x-dropdown.divider>

                <x-dropdown.item
                    icon="iconoir-git-fork"
                    description="This blog is open source and the codebase becomes bigger fast. There's a lot to learn and this is free."
                    href="https://github.com/benjamincrozat/blog-v5"
                    target="_blank"
                >
                    Fork the source code
                </x-dropdown.item>

                <x-dropdown.item
                    icon="heroicon-o-chart-bar"
                    description="See how my blog is doing by directly checking the numbers because, why not?"
                    href="https://benjamincrozat.pirsch.io/?domain=benjamincrozat.com&interval=30d&scale=day"
                    target="_blank"
                >
                    My analytics dashboard
                </x-dropdown.item>

                <x-dropdown.divider>
                    Follow me
                </x-dropdown.divider>

                <x-dropdown.item
                    icon="heroicon-o-rss"
                    href="{{ route('feeds.main') }}"
                >
                    Atom feed
                </x-dropdown.item>

                <x-dropdown.item
                    icon="iconoir-github"
                    href="https://github.com/benjamincrozat"
                    target="_blank"
                >
                    GitHub
                </x-dropdown.item>

                <x-dropdown.item
                    icon="iconoir-linkedin"
                    href="https://www.linkedin.com/in/benjamincrozat"
                    target="_blank"
                >
                    LinkedIn
                </x-dropdown.item>

                <x-dropdown.item
                    icon="iconoir-x"
                    href="https://x.com/benjamincrozat"
                    target="_blank"
                >
                    X
                </x-dropdown.item>
            </x-slot>
        </x-dropdown>
    </div>
</nav>
