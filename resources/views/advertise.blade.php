<x-app
    title="Advertise to {{ Number::format($visitors) }} developers"
    :hide-ad="true"
>
    <div class="container text-center md:text-xl xl:max-w-(--breakpoint-lg)">
        <img
            loading="lazy"
            src="{{ Vite::asset('resources/img/icons/megaphone.png') }}"
            class="mx-auto mb-8 h-24 md:h-28 lg:h-32"
        />

        <h1 class="text-3xl font-medium tracking-tight text-black lg:text-4xl xl:text-7xl">
            Advertise to <span class="text-blue-600">{{ Number::format($visitors) }}</span>&nbsp;developers
        </h1>

        <p class="mt-1 text-lg text-gray-800 md:mt-2 md:text-xl lg:text-2xl">
            This is the right place to show off your product.
        </p>

        <x-btn
            primary
            size="md"
            href="#products"
            class="table mx-auto mt-8 lg:mt-12"
        >
            Learn more
        </x-btn>
    </div>

    <x-section
        title="Trusted by"
        class="mt-24 text-center"
    >
        <div class="flex flex-wrap gap-y-4 gap-x-8 justify-center items-center px-4 md:gap-x-12 lg:gap-x-16">
            <x-icon-coderabbit class="flex-none h-6 sm:h-7" />
            <x-icon-kinsta class="flex-none -translate-y-px h-[1.15rem] sm:h-6" />
            <div class="text-2xl font-bold text-red-600 sm:text-3xl">larajobs</div>
            <x-icon-meilisearch class="flex-none h-6 translate-y-px sm:h-7" />
            <x-icon-sevalla class="flex-none h-9 sm:h-10" />
        </div>
    </x-section>

    <x-section
        title="The past 30 days on my blog"
        class="mt-24 xl:max-w-(--breakpoint-lg)"
    >
        <div class="grid grid-cols-2 gap-2 mt-6 text-center md:text-xl">
            <div class="p-2 text-black bg-gray-50 rounded-xl md:p-4">
                <x-heroicon-o-user class="mx-auto mb-2 h-8 text-gray-600 md:h-10 lg:h-12" />
                <div class="text-3xl font-medium md:text-5xl">{{ Number::format($visitors) }}</div>
                <div class="md:text-xl lg:text-xl">visitors</div>
            </div>

            <div class="p-2 text-black bg-gray-50 rounded-xl md:p-4">
                <x-heroicon-o-window class="mx-auto mb-2 h-8 text-gray-600 md:h-10 lg:h-12" />
                <div class="text-3xl font-medium md:text-5xl">{{ $views }}</div>
                <div class="md:text-xl lg:text-xl">page views</div>
            </div>

            <div class="p-2 text-black bg-gray-50 rounded-xl md:p-4">
                <x-heroicon-o-user-group class="mx-auto mb-2 h-8 text-gray-600 md:h-10 lg:h-12" />
                <div class="text-3xl font-medium md:text-5xl">{{ $sessions }}</div>
                <div class="md:text-xl lg:text-xl">sessions</div>
            </div>

            <div class="p-2 text-black bg-gray-50 rounded-xl md:p-4">
                <x-heroicon-o-computer-desktop class="mx-auto mb-2 h-8 text-gray-600 md:h-10 lg:h-12" />
                <div class="text-3xl font-medium md:text-5xl">{{ $desktop }}%</div>
                <div class="md:text-xl lg:text-xl">on desktop</div>
            </div>
        </div>
    </x-section>

    <x-section
        title="Write a sponsored article"
        id="products"
        class="mt-24 lg:max-w-(--breakpoint-md)"
    >
        <div class="p-8 text-center bg-white rounded-xl ring-1 shadow-xl shadow-black/5 ring-black/10">
            <p class="text-7xl font-medium">500€</p>

            <p class="text-sm text-gray-500">one-time payment</p>

            <p class="mt-8 font-medium">What you get:</p>

            <ul class="grid gap-2 place-content-center mt-2 text-left">
                <li class="flex gap-2 items-start">
                    <x-heroicon-o-check class="flex-none text-green-600 translate-y-1 size-4" />
                    Featured on top of every article from Monday to Sunday
                </li>

                <li class="flex gap-2 items-start">
                    <x-heroicon-o-check class="flex-none text-green-600 translate-y-1 size-4" />
                    Access to {{ Number::format($visitors) }} monthly developers
                </li>

                <li class="flex gap-2 items-start">
                    <x-heroicon-o-check class="flex-none text-green-600 translate-y-1 size-4" />
                    A backlink on a DR 51 domain
                </li>

                <li class="flex gap-2 items-start">
                    <x-heroicon-o-check class="flex-none text-green-600 translate-y-1 size-4" />
                    <span>A secured position on the blog, <strong class="font-medium">forever</strong></span>
                </li>
            </ul>

            <x-btn href="{{ route('checkout.start', 'sponsored_article') }}" primary class="mt-8">
                Pay and publish
            </x-btn>

            <p class="mt-8 text-balance">Once done, <a href="mailto:hello@benjamincrozat.com" class="font-medium underline">email me</a> with your article. Here are the <a wire:navigate href="{{ route('advertise.guidelines') }}" class="font-medium underline">guidelines</a>. It will be published as soon as possible today or tomorrow.</p>
        </div>
    </x-section>
</x-app>
