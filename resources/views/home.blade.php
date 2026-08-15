{{--
Displays the home view.
--}}

<x-app :title="config('app.name')">
    <div class="container xl:max-w-(--breakpoint-lg)">
        <x-typography.headline>
            The hub for <span class="text-blue-600">50,000</span>+ <span class="line-through">developers</span> agents, monthly
        </x-typography.headline>

        <x-typography.subheadline class="mt-6 md:mt-10">
            Stay ahead in web development with a good dose of AI and everything else.
        </x-typography.subheadline>
    </div>

    <x-section id="latest" class="mt-24 md:mt-32">
        <div class="flex gap-4 justify-between items-baseline mb-8">
            <x-typography.heading tag="h2" class="text-left!">
                Latest posts
            </x-typography.heading>

            <a
                wire:navigate
                href="{{ route('posts.index') }}"
                class="shrink-0 font-medium underline transition-colors hover:text-blue-600 underline-offset-4 decoration-1 decoration-black/30 hover:decoration-blue-600/50"
            >
                Browse all articles
            </a>
        </div>

        @if ($latest->isNotEmpty())
            <x-posts-grid :posts="$latest" />
        @endif
    </x-section>

    <x-section id="links" class="mt-24 md:mt-32">
        <div class="flex gap-4 justify-between items-baseline mb-8">
            <x-typography.heading tag="h2" class="text-left!">
                Latest links
            </x-typography.heading>

            <a
                wire:navigate
                href="{{ route('links.index') }}"
                class="shrink-0 font-medium underline transition-colors hover:text-blue-600 underline-offset-4 decoration-1 decoration-black/30 hover:decoration-blue-600/50"
            >
                Browse all links
            </a>
        </div>

        @if ($links->isNotEmpty())
            <x-links-grid :$links :show-images="false" />
        @endif
    </x-section>

</x-app>
