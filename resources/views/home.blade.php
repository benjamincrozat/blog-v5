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

    <x-section title="Latest posts" id="latest" class="mt-24 md:mt-32">
        @if ($latest->isNotEmpty())
            <x-posts-grid :posts="$latest" />
        @endif

        <x-btn
            primary
            wire:navigate
            href="{{ route('posts.index') }}"
            class="table mx-auto mt-16"
        >
            Browse all articles
        </x-btn>
    </x-section>

    <x-section title="Latest links" id="links" class="mt-24 md:mt-32">
        @if ($links->isNotEmpty())
            <x-links-grid :$links :show-images="false" />
        @endif

        <x-btn
            primary
            wire:navigate
            href="{{ route('links.index') }}"
            class="table mx-auto mt-16"
        >
            Browse all links
        </x-btn>
    </x-section>

</x-app>
