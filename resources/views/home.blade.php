{{--
Displays the home view.
--}}

<x-app :title="config('app.name')">
    <div class="container xl:max-w-(--breakpoint-lg)">
        <div class="text-xl tracking-tight font-normal text-center text-black mb-4">
            <div class="font-handwriting">Including a ton of scrapers too!</div>
            <x-heroicon-o-arrow-down class="size-4 mx-auto mt-1" />
        </div>

        <x-typography.headline>
            The hub for <span class="text-blue-600">{{ Number::format($visitors) }}</span>+ web developers, monthly
        </x-typography.headline>

        <x-typography.subheadline class="mt-6 md:mt-10">
            Stay ahead in web development with a good dose of AI and everything else.
        </x-typography.subheadline>

        <div class="flex gap-2 justify-center items-center mt-7 text-center md:mt-11">
            <x-btn
                size="md"
                wire:navigate
                href="{{ route('authors.show', 'benjamin-crozat') }}"
            >
                Who the F are you?
            </x-btn>

            <x-btn
                primary
                size="md"
                wire:navigate
                href="{{ route('posts.index') }}"
            >
                Start reading
            </x-btn>
        </div>
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

    <x-section
        title="Great tools for developers"
        class="mt-24 md:mt-32"
    >
        <div class="grid gap-8 mt-8 lg:grid-cols-2">
            <x-tools.tinkerwell />
            <x-tools.tower />
            <x-tools.fathom-analytics />
            <x-tools.cloudways />
            <x-tools.mailcoach />
            <x-tools.wincher />
            <x-tools.uptimia />
        </div>
    </x-section>

    <x-section title="Latest links" id="links" class="mt-24 md:mt-32">
        @if ($links->isNotEmpty())
            <x-links-grid :$links />
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

    @if ($aboutUser)
        <x-section title="About {{ $aboutUser->name }}" id="about" class="mt-24 lg:max-w-(--breakpoint-md) md:mt-32">
            <x-prose>
                <img
                    loading="lazy"
                    src="{{ $aboutUser->avatar }}"
                    alt="{{ $aboutUser->name }}"
                    class="float-right mt-4 ml-4 rounded-full! size-20 sm:size-28 md:size-32"
                />

                {!! \App\Markdown\MarkdownRenderer::parse($aboutUser->biography) !!}
            </x-prose>
        </x-section>
    @endif
</x-app>
