<x-app>
    <div class="container mt-16 text-center">
        <div class="font-bold text-black text-4xl/none md:text-5xl lg:text-7xl text-balance">
            <span class="text-blue-600">{{ Number::format(random_int(24000, 24999)) }}</span> monthly visitors read my blog
        </div>

        <div class="mt-5 leading-tight text-black/75 text-lg/tight sm:text-xl/tight md:text-2xl/tight md:mt-8 lg:text-3xl text-balance">
            No matter how senior you are, I have something for you. Ready?
        </div>

        <div class="flex items-center justify-center gap-2 text-center mt-7 md:mt-11">
            <x-btn
                size="md"
                href="#about"
            >
                Who the F are you?
            </x-btn>

            <x-btn
                primary
                size="md"
                href="#latest"
            >
                Start reading
            </x-btn>
        </div>
    </div>

    <x-section title="They support the blog" class="mt-24 md:mt-32 lg:max-w-screen-md">
        <div class="flex flex-wrap justify-center mt-8 gap-y-4 gap-x-12">
            <a href="https://beyondco.de/?utm_source=benjamincrozat&utm_medium=logo&utm_campaign=benjamincrozat" target="_blank">
                <x-icon-beyond-code class="h-7 md:h-8" />
                <span class="sr-only">Beyond Code</span>
            </a>

            <a href="https://nobinge.ai" target="_blank">
                <x-icon-nobinge class="h-6 md:h-7" />
                <span class="sr-only">Nobinge</span>
            </a>

            <a href="https://redirect.pizza?utm_source=benjamincrozat.com&utm_medium=logo&utm_campaign=sponsorship" target="_blank">
                <img
                    loading="lazy"
                    src="{{ Vite::asset('resources/img/redirect-pizza.png') }}"
                    width="400"
                    height="124"
                    alt="redirect.pizza"
                    class="flex-shrink-0 w-auto h-9 snap-start scroll-ml-4"
                />
            </a>
        </div>

        <div class="text-center sm:text-xl mt-7">
            If you like my blog, please check out these development/education-centric products that will help you as a developer without a doubt.
        </div>
    </x-section>

    <x-section title="Latest posts" id="latest" class="mt-24 md:mt-32">
        @if ($latest->isNotEmpty())
            <ul class="grid gap-16 mt-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($latest as $post)
                    <li>
                        <x-post :$post />
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="mt-16 text-center">
            <x-btn
                primary
                size="md"
                wire:navigate
                href="{{ route('posts.index') }}"
            >
                Browse all articles
            </x-btn>
        </div>
    </x-section>

    <x-section title="About Benjamin Crozat" id="about" class="mt-24 lg:max-w-screen-md md:mt-32">
        <x-prose class="mt-8">
            <img
                src="https://www.gravatar.com/avatar/d58b99650fe5d74abeb9d9dad5da55ad?s=256"
                alt="Benjamin Crozat"
                class="float-right mt-4 ml-4 rounded-full ring-1 ring-black/5 size-28 md:size-32"
            />

            {!! Str::markdown(<<<MARKDOWN
Hi! I'm from the South of France and I've been a self-taught web developer since 2006. When I started learning PHP and JavaScript, PHP 4 was still widely used, Internet Explorer 6 ruled the world, and we used DHTML to add falling snow on websites.

Being able to educate myself for free on the web changed my life for the better. Giving back to the community was a natural direction in my career and I truly enjoy it.

Therefore, I decided to take action:

1. I launched this blog in September 2022 with the goal to be in everyone's Google search. I get more than tens of thousands of monthly clicks from it and even more visits overall (my [analytics dashboard](https://benjamincrozat.pirsch.io/?domain=benjamincrozat.com&interval=30d&scale=day) is public by the way).
2. I also started growing my [X (formerly Twitter) account](https://x.com/benjamincrozat) at the same time, which has now over 6,000 followers.
3. All the content I write is free thanks my sponsors.

I also want to be completely free with my time and make a living with my own products. In April 2024, I launched [Nobinge](https://nobinge.ai/), a tool to summarize and chat with your content, including YouTube videos.

Believe me, I'm just getting started!
MARKDOWN) !!}
        </x-prose>
    </x-section>
</x-app>
