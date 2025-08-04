<x-app
    :canonical="$post->canonical_url"
    :description="$post->description"
    :hide-ad="$post->is_commercial"
    :image="$post->image_url"
    :title="! empty($post->serp_title) ? $post->serp_title : $post->title"
>
    <div @class([
        'container',
        '2xl:max-w-(--breakpoint-xl) grid lg:grid-cols-12 gap-16 lg:gap-12' => ! $post->is_commercial,
        'lg:max-w-(--breakpoint-md)' => $post->is_commercial,
    ])>
        <div @class([
            'lg:col-span-8 xl:col-span-9' => ! $post->is_commercial,
        ])>
            <article>
                @if ($post->hasImage())
                    <img
                        fetchpriority="high"
                        src="{{ $post->image_url }}"
                        alt="{{ $post->title }}"
                        width="1280"
                        height="720"
                        class="object-cover mb-11 w-full rounded-xl ring-1 shadow-xl ring-black/5 aspect-video"
                    />
                @endif

                <x-categories :categories="$post->categories" class="justify-center mt-11 mb-8" />

                <p class="text-sm font-normal tracking-widest text-center uppercase md:text-base">
                    {{ trans_choice(':count minute|:count minutes', $post->read_time) }}
                    read
                </p>

                <h1 class="mt-2 font-medium tracking-tight text-center text-black text-balance text-3xl/none sm:text-4xl/none lg:text-5xl/none">
                    {{ $post->title }}
                </h1>

                <div class="grid grid-cols-2 gap-4 mt-12 text-sm leading-tight md:mt-16 md:grid-flow-col md:grid-cols-none md:auto-cols-fr">
                    <div class="p-3 text-center bg-gray-50 rounded-lg">
                        <x-heroicon-o-calendar class="mx-auto mb-2 opacity-75 size-6" />

                        @if ($post->modified_at)
                            Modified
                        @elseif ($post->published_at)
                            Published
                        @else
                            Drafted
                        @endif

                        <br />

                        {{ ($post->modified_at ?? $post->published_at ?? $post->created_at)->isoFormat('ll') }}
                    </div>

                    <a
                        wire:navigate
                        href="{{ route('authors.show', $post->user) }}"
                        data-pirsch-event="Clicked post author"
                        data-pirsch-meta-name="{{ $post->user->name }}"
                    >
                        <div class="p-3 h-full text-center bg-gray-50 rounded-lg transition-colors hover:bg-blue-50 hover:text-blue-900">
                            <img
                                loading="lazy"
                                src="{{ $post->user->avatar }}"
                                alt="{{ $post->user->name }}"
                                class="mx-auto mb-2 rounded-full size-6"
                            />

                            Written by<br />
                            {{ $post->user->name }}
                        </div>
                    </a>

                    @if (! $post->is_commercial)
                        <a
                            href="#comments"
                            data-pirsch-event="Clicked comments"
                            data-pirsch-meta-post="{{ $post->title }}"
                        >
                            <div @class([
                                'p-3 text-center bg-gray-50 rounded-lg transition-colors hover:bg-blue-50 hover:text-blue-900 h-full',
                                'text-blue-600 bg-blue-50!' => $post->comments_count,
                            ])>
                                <x-heroicon-o-chat-bubble-oval-left-ellipsis class="mx-auto mb-2 opacity-75 size-6" />
                                {{ $post->comments_count }}<br />
                                {{ trans_choice('comment|comments', $post->comments_count) }}
                            </div>
                        </a>
                    @endif

                    <x-dropdown>
                        <x-slot:btn
                            class="p-3 w-full h-full text-center bg-gray-50 rounded-lg transition-colors hover:bg-blue-50 hover:text-blue-900"
                        >
                            <x-heroicon-o-ellipsis-horizontal
                                class="mx-auto transition-transform size-6 md:size-7"
                                x-bind:class="{ 'rotate-90': open }"
                            />
                            Actions
                        </x-slot>

                        <x-slot:items>
                            @if ($user?->isAdmin())
                                <x-dropdown.divider>
                                    Admin
                                </x-dropdown.divider>

                                <x-dropdown.item
                                    icon="heroicon-o-pencil-square"
                                    href="{{ route('filament.admin.resources.posts.edit', $post) }}"
                                >
                                    Edit article
                                </x-dropdown.item>
                            @endif

                            <x-dropdown.divider>
                                Chat
                            </x-dropdown.divider>

                            <x-dropdown.item
                                icon="icon-openai"
                                :href="'https://chatgpt.com/?q=' . urlencode($post->toPrompt())"
                                target="_blank"
                            >
                                Ask ChatGPT
                            </x-dropdown.item>

                            <x-dropdown.item
                                icon="icon-claude"
                                :href="'https://claude.ai/new?q=' . urlencode($post->toPrompt())"
                                target="_blank"
                            >
                                Ask Claude
                            </x-dropdown.item>

                            @if ($post->quiz && ! $post->is_commercial)
                                <x-dropdown.divider>
                                    Test yourself
                                </x-dropdown.divider>

                                <x-dropdown.item
                                    icon="heroicon-o-question-mark-circle"
                                    wire:navigate
                                    href="{{ route('quizzes.show', $post->quiz) }}"
                                >
                                    Take a quiz
                                </x-dropdown.item>
                            @endif

                            <x-dropdown.divider>
                                Share
                            </x-dropdown.divider>

                            <x-dropdown.item
                                icon="iconoir-facebook"
                                :href="'https://www.facebook.com/sharer/sharer.php?u=' . urlencode(route('posts.show', $post))"
                                target="_blank"
                            >
                                Share on Facebook
                            </x-dropdown.item>

                            <x-dropdown.item
                                icon="iconoir-linkedin"
                                :href="'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode(route('posts.show', $post)) . '&title=' . urlencode($post->title)"
                                target="_blank"
                            >
                                Share on LinkedIn
                            </x-dropdown.item>

                            <x-dropdown.item
                                icon="iconoir-x"
                                :href="'https://x.com/intent/tweet?url=' . urlencode(route('posts.show', $post)) . '&text=' . urlencode($post->title)"
                                target="_blank"
                            >
                                Share on X
                            </x-dropdown.item>
                        </x-slot>
                    </x-dropdown>
                </div>

                @if (! empty($headings = extract_headings_from_markdown($post->content)))
                    <x-table-of-contents
                        :$headings
                        class="mt-4 ml-0"
                    />
                @endif

                <x-prose class="mt-8">
                    {!! $post->formatted_content !!}

                    @if ($post->link)
                        <p>
                            <a
                                href="{{ $post->link->url }}"
                                target="_blank"
                            >
                                Read more on {{ $post->link->domain }} →
                            </a>
                        </p>
                    @endif

                    @if (! empty($post->recommendedPosts) && ! $post->is_commercial)
                        <hr />

                        <p>Did you like this article? Then, keep learning:</p>

                        <ul>
                            @foreach ($post->recommendedPosts as $recommendedPost)
                                <li>
                                    <a
                                        wire:navigate
                                        href="{{ route('posts.show', $recommendedPost) }}"
                                        data-pirsch-event="Clicked on recommended post"
                                        data-pirsch-meta-title="{{ $recommendedPost->title }}"
                                    >
                                        {{ trim($recommendedPost->reason, '.') }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </x-prose>

                @if ($post->quiz)
                    <a wire:navigate href="{{ route('quizzes.show', $post->quiz) }}">
                        <div class="flex flex-wrap gap-6 p-4 mt-8 bg-orange-50 rounded-xl sm:gap-4 sm:flex-nowrap md:gap-8 md:p-8 text-orange-950">
                            <div class="sm:w-2/3">
                                <p class="font-medium sm:text-xl">
                                    Think you got it all? Prove it.
                                </p>

                                <p class="mt-2">
                                    Time to separate the skimmers from the pros. Take this quick quiz to see if you actually learned something or just scrolled fast.
                                </p>

                                <x-btn primary class="mt-[1.15rem] bg-orange-500 hover:bg-orange-600 px-4! rounded-lg! cursor-pointer">
                                    Take the quiz
                                </x-btn>
                            </div>

                            <div class="p-4 pt-0 sm:w-1/3 sm:p-0">
                                <img src="{{ Vite::asset('resources/img/illustrations/quiz.png') }}" />
                            </div>
                        </div>
                    </a>
                @endif

                <div class="p-4 mt-8 text-center bg-gray-100 rounded-xl md:p-8 md:text-xl/tight">
                    <p>Help me reach more people by sharing this article on social media!</p>

                    <ul class="inline-flex gap-2 mt-4 md:gap-3">
                        <li>
                            <a
                                href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('posts.show', $post)) }}"
                                target="_blank"
                                data-pirsch-event='Clicked on "Facebook"'
                                class="grid place-items-center size-12 text-white bg-[#0766FF] rounded-md"
                            >
                                <x-iconoir-facebook class="size-6" />
                                <span class="sr-only">Facebook</span>
                            </a>
                        </li>

                        <li>
                            <a
                                href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('posts.show', $post)) }}&title={{ urlencode($post->title) }}"
                                target="_blank"
                                data-pirsch-event='Clicked on "LinkedIn"'
                                class="grid place-items-center size-12 text-white bg-[#0B66C2] rounded-md"
                            >
                                <x-iconoir-linkedin class="size-6" />
                                <span class="sr-only">Linkedin</span>
                            </a>
                        </li>

                        <li>
                            <a
                                href="https://x.com/intent/tweet?url={{ urlencode(route('posts.show', $post)) }}&text={{ urlencode($post->title) }}"
                                target="_blank"
                                data-pirsch-event='Clicked on "X"'
                                class="grid place-items-center text-white bg-gray-900 rounded-md size-12"
                            >
                                <x-iconoir-x class="size-6" />
                                <span class="sr-only">X</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </article>

            @if (! $post->is_commercial)
                <div class="mt-24">
                    <livewire:comments :post-id="$post->id" />
                </div>

                <section class="mt-24">
                    <x-heading>
                        Great deals for developers
                    </x-heading>

                    <div class="grid gap-4 mt-8">
                        <x-deals.tower />
                        <x-deals.fathom-analytics />
                        <x-deals.cloudways />
                        <x-deals.mailcoach />
                        <x-deals.wincher />
                        <x-deals.uptimia />
                    </div>
                </section>
            @endif
        </div>

        @if (! $post->is_commercial)
            <div class="lg:col-span-4 xl:col-span-3">
                <x-ads.sidebar.sevalla class="max-w-[280px] mx-auto lg:max-w-none lg:mx-0" />

                <a
                    href="{{ route('deals') }}"
                    class="hidden lg:block"
                    data-pirsch-event="Clicked on deals in sidebar"
                >
                    <p class="p-4 mt-4 leading-tight rounded-xl text-balance bg-gray-100/75">
                        <strong class="font-medium">I have even more deals for developers.</strong> Services, apps, and all kinds of tools at a discount. <span class="font-medium underline">Check available deals →</span>
                    </p>
                </a>

                @if ($post->quiz)
                    <div class="hidden mt-16 lg:block">
                        <x-heading tag="p" class="!text-left">Take a quiz</x-heading>

                        @php
                        $questions = $post->quiz->questions->count() > 3
                            ? $post->quiz->questions->take(3)
                            : $post->quiz->questions;
                        @endphp

                        <ul class="grid gap-2 mt-2">
                            @foreach ($questions as $question)
                                <li class="px-4 py-3 leading-tight bg-gray-100 rounded-md">
                                    <span class="font-medium">{{ $loop->iteration }}.</span> {{ $question->question }}
                                </li>
                            @endforeach

                            @if ($post->quiz->questions->count() > 3)
                                <li class="px-4 py-3 font-medium leading-tight text-center rounded-md border border-gray-200">
                                    And {{ $post->quiz->questions->count() - 3 }} more
                                </li>
                            @endif
                        </ul>

                        <x-btn
                            primary
                            wire:navigate
                            href="{{ route('quizzes.show', $post->quiz) }}"
                            class="mt-[1.05rem] w-full text-center"
                        >
                            Test your knowledge
                        </x-btn>
                    </div>
                @endif

                @if ($latestComment)
                    <div class="hidden mt-16 lg:block">
                        <p class="font-bold tracking-widest text-black uppercase text-balance">
                            Latest comment
                        </p>

                        <div class="flex gap-4 mt-4">
                            <img
                                loading="lazy"
                                src="{{ $latestComment->user->avatar }}"
                                alt="{{ $latestComment->user->name }}"
                                class="flex-none mt-1 rounded-full ring-1 shadow-sm shadow-black/5 ring-black/10 size-7 md:size-8"
                            />

                            <div>
                                <p>
                                    <a
                                        href="{{ $latestComment->user->github_data['user']['html_url'] }}"
                                        target="_blank"
                                        class="font-medium"
                                        data-pirsch-event="Clicked on latest comment's username"
                                        data-pirsch-meta-name="{{ $latestComment->user->name }}"
                                    >
                                        {{ $latestComment->user->name }}
                                    </a>

                                    <span class="ml-1 text-gray-500">
                                        {{ $latestComment->created_at->diffForHumans(short: true) }}
                                    </span>
                                </p>

                                <x-prose class="mt-1 leading-normal text-gray-500">
                                    {!! $latestComment->truncated !!}
                                </x-prose>

                                <p class="mt-1 text-right">
                                    <a
                                        href="#comments"
                                        class="font-medium underline"
                                        data-pirsch-event='Clicked "check comments"'
                                    >
                                        Check comments →
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="hidden mt-16 lg:block">
                    <p class="font-bold tracking-widest text-black uppercase text-balance">
                        Follow me
                    </p>

                    <ul class="grid gap-2 mt-4">
                        <li>
                            <x-btn
                                href="{{ route('feeds.main') }}"
                                data-pirsch-event="Clicked on Atom feed"
                                class="group flex! gap-3 items-center px-4! py-3! text-white! bg-orange-400!"
                            >
                                <x-heroicon-o-rss class="size-4 translate-y-[.5px]" />
                                <p class="font-medium">Atom feed</p>
                            </x-btn>
                        </li>

                        <li>
                            <x-btn
                                href="https://www.linkedin.com/in/benjamincrozat/"
                                target="_blank"
                                data-pirsch-event="Clicked on LinkedIn"
                                class="group flex! gap-3 items-center px-4! py-3! text-white! bg-[#0B66C2]!"
                            >
                                <x-iconoir-linkedin class="size-4 translate-y-[.5px]" />
                                <p class="font-medium">LinkedIn</p>
                            </x-btn>
                        </li>

                        <li>
                            <x-btn
                                href="https://github.com/benjamincrozat"
                                target="_blank"
                                data-pirsch-event="Clicked on GitHub"
                                class="!flex gap-3 items-center bg-white! !px-4 !py-3 ring-1 group ring-black/10"
                            >
                                <x-iconoir-github class="size-4 translate-y-[.5px]" />
                                <p class="font-medium">GitHub</p>
                            </x-btn>
                        </li>

                        <li>
                            <x-btn
                                href="https://x.com/benjamincrozat"
                                target="_blank"
                                data-pirsch-event="Clicked on X"
                                class="group flex! gap-3 items-center px-4! py-3! bg-black! text-white!"
                            >
                                <x-iconoir-x class="size-4 translate-y-[.5px]" />
                                <p class="font-medium">X</p>
                            </x-btn>
                        </li>
                    </ul>
                </div>
            </div>
        @endif
    </div>

    {{-- This kind of information is only relevant for published posts. --}}
    @if ($post->published_at)
        <script type="application/ld+json">
            {
                "@@context": "https://schema.org",
                "@type": "Article",
                "author": {
                    "@type": "Person",
                    "name": "{{ $post->user->name }}",
                    "url": "{{ route('home') }}#about"
                },
                "headline": "{{ $post->title }}",
                "description": "{{ $post->description }}",
                "image": "{{ $post->image_url }}",
                "datePublished": "{{ $post->published_at->toIso8601String() }}",
                "dateModified": "{{ $post->modified_at?->toIso8601String() ?? $post->published_at->toIso8601String() }}"
            }
        </script>
    @endif
</x-app>
