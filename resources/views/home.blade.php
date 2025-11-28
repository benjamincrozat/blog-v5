<x-app :title="config('app.name')">
    <div class="container xl:max-w-(--breakpoint-lg) text-center">
        <div class="font-medium tracking-tight text-black text-4xl/none md:text-5xl lg:text-7xl text-balance">
            The modern hub for
            <span class="text-blue-600">{{ Number::format($visitors) }}</span> monthly developers
        </div>

        <div class="mt-5 text-balance tracking-tight text-black/75 text-lg/tight sm:text-xl/tight md:text-2xl/tight md:mt-8">
            Let me help you survive in this industry without getting replaced by AI. I'll tell you about the latest news, techniques, and tools.
        </div>

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

    <div class="grid container lg:grid-cols-12 gap-16 mt-24 md:mt-32">
        @if ($popular->isNotEmpty())
            <section id="popular" class="lg:col-span-6">
                <x-heading class="text-left! mb-[.35rem] flex items-center gap-2">
                    Popular articles

                    <x-help-btn>
                        The most popular articles people click on.<br />
                        They are mostly driven by search engines.
                    </x-help-btn>
                </x-heading>

                <div class="h-px bg-linear-to-r from-gray-300 to-transparent"></div>

                <x-posts-list :posts="$popular" class="mt-8" />

                <div class="mt-7">
                    <a
                        wire:navigate
                        href="{{ route('posts.index') }}"
                        class="underline font-medium hover:text-blue-600 transition-colors"
                        data-pirsch-event='Clicked "browse all articles"'
                    >
                        Browse all articles →
                    </a>
                </div>
            </section>

            <section id="jobs" class="lg:col-span-6">
                <x-heading class="text-left! mb-[.35rem] flex items-center gap-2">
                    Latest jobs ({{ $recentJobsCount }})

                    <x-help-btn>
                        The counter shows the number of jobs posted in the last 30 days.
                    </x-help-btn>
                </x-heading>

                <div class="h-px bg-linear-to-r from-gray-300 to-transparent"></div>

                <x-jobs-list :jobs="$jobs" class="mt-8" />

                <div class="mt-7">
                    <a
                        wire:navigate
                        href="{{ route('jobs.index') }}"
                        class="underline font-medium hover:text-blue-600 transition-colors"
                        data-pirsch-event='Clicked "browse all jobs"'
                    >
                        Browse all jobs →
                    </a>
                </div>
            </section>
        @endif
    </div>

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

                {!! Markdown::parse($aboutUser->biography) !!}
            </x-prose>
        </x-section>
    @endif
</x-app>
