<x-app
    title="Show off your business to {{ Number::format($visitors) }} developers"
    :hide-top-ad="true"
    :hide-sticky-carousel="true"
>
    <div class="container md:text-xl xl:max-w-(--breakpoint-lg)">
        <x-typography.headline>
            Show off your business to <span class="text-blue-600">{{ Number::format($visitors) }}</span>&nbsp;developers
        </x-typography.headline>

        <x-typography.subheadline class="mt-6 md:mt-10">
            Access an audience of developers while bypassing adblockers.
        </x-typography.subheadline>

        <div class="flex flex-wrap md:flex-nowrap md:text-left gap-4 justify-center">
            <x-btn
                size="md"
                href="https://benjamincrozat.pirsch.io/?domain=benjamincrozat.com&interval=30d&scale=day"
                target="_blank"
                class="mt-8 lg:mt-12"
            >
                My analytics dashboard
            </x-btn>

            <x-btn
                primary
                size="md"
                href="#products"
                class="mt-8 lg:mt-12"
            >
                Check offers
            </x-btn>
        </div>
    </div>

    <x-section
        title="Previously sponsored by"
        class="mt-24 text-center"
    >
        <div class="flex flex-wrap md:flex-nowrap text-center md:text-left gap-y-4 gap-x-8 justify-center items-center px-4 md:gap-x-12 lg:gap-x-16">
            <x-icon-coderabbit class="flex-none translate-y-[.5px] sm:translate-y-0 h-[1.35rem] sm:h-[1.65rem]" />
            <x-icon-kinsta class="flex-none -translate-y-px sm:translate-y-0 h-[1.15rem] sm:h-[1.35rem]" />
            <div class="text-2xl font-bold text-red-600 sm:text-3xl">larajobs</div>
            <x-icon-meilisearch class="flex-none h-6 translate-y-px sm:h-8" />
            <x-icon-sevalla class="flex-none h-9 sm:h-10" />
        </div>
    </x-section>

    <a name="products"></a>

    <div class="grid gap-8">
        <div class="container xl:max-w-(--breakpoint-lg)">
            <section
                id="top-banner"
                class="mt-24 bg-white shadow-md shadow-black/10 ring-1 ring-black/10 rounded-xl p-4 md:p-8"
            >
                <x-typography.heading>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" class="size-16 mx-auto mb-2"><path d="M40 56h176v40H40z" opacity=".2"/><path d="M216 40H40c-8.836556 0-16 7.163444-16 16v144c0 8.836556 7.163444 16 16 16h176c8.836556 0 16-7.163444 16-16V56c0-8.836556-7.163444-16-16-16Zm0 16v40H40V56h176Zm0 144H40v-88h176v88Z"/></svg>
                    Stay on top of every page
                    <span class="bg-yellow-500 inline-block ml-2 font-normal p-2 leading-tight px-4 rounded-full normal-case tracking-normal">Sold out!</span>
                </x-typography.heading>

                <div class="flex flex-wrap md:flex-nowrap text-center md:text-left items-start mt-8 justify-between gap-8 md:gap-16">
                    <div class="md:w-1/2 w-full">
                        <p class="font-medium">What you get:</p>
                        
                        <ul class="grid gap-2 mt-2 text-left">
                            <li class="flex gap-2 items-start">
                                <x-heroicon-o-check class="flex-none text-green-600 translate-y-1 size-4" />
                                Featured on top of every page.
                            </li>

                            <li class="flex gap-2 items-start">
                                <x-heroicon-o-check class="flex-none text-green-600 translate-y-1 size-4" />
                                Access to {{ Number::format($visitors) }} monthly developers.
                            </li>

                            <li class="flex gap-2 items-start">
                                <x-heroicon-o-check class="flex-none text-green-600 translate-y-1 size-4" />
                                Bypass any adblocker.
                            </li>
                
                            <li class="flex gap-2 items-start">
                                <x-heroicon-o-check class="flex-none text-green-600 translate-y-1 size-4" />
                                A backlink on a DR 45-50 domain.
                            </li>
                        </ul>
                    </div>

                    <div class="md:w-1/2 w-full">
                        <div class="inline-flex items-baseline gap-2 text-xl">
                            <p class="text-7xl font-medium">
                                {{ Number::currency(600, 'EUR', precision: 0) }}
                            </p>

                            monthly
                        </div>

                        <div class="flex flex-wrap md:flex-nowrap text-center md:text-left items-center gap-4 mt-6 justify-center md:justify-start">
                            <x-btn href="https://benjamincrozat.com/php-85" target="_blank">
                                Live example
                            </x-btn>
                            
                            <x-btn primary disabled>
                                Sold out!
                            </x-btn>
                        </div>
            
                        <p class="mt-6 md:mt-8 text-balance">Once done, <a href="mailto:hello@benjamincrozat.com" class="font-medium underline">email me</a> with the necessary information and you will be live within 24 hours.</p>
                    </div>
                </div>
            </section>
        </div>

        <div class="container xl:max-w-(--breakpoint-lg)">
            <x-section
                id="sidebar"
                class="bg-white shadow-md shadow-black/10 ring-1 ring-black/10 rounded-xl p-4 md:p-8"
            >
                <x-typography.heading>
                    <x-phosphor-sidebar-duotone class="size-16 mx-auto mb-2 -scale-x-100" />
                    Be visible in the sidebar
                    <span class="bg-yellow-500 inline-block ml-2 font-normal p-2 leading-tight px-4 rounded-full normal-case tracking-normal">Sold out!</span>
                </x-typography.heading>

                <div class="flex flex-wrap md:flex-nowrap text-center md:text-left items-start mt-8 justify-between gap-8 md:gap-16">
                    <div class="md:w-1/2 w-full">
                        <p class="font-medium">What you get:</p>
                        
                        <ul class="grid gap-2 mt-2 text-left">
                            <li class="flex gap-2 items-start">
                                <x-heroicon-o-check class="flex-none text-green-600 translate-y-1 size-4" />
                                Visibility in the sidebar on every article.
                            </li>

                            <li class="flex gap-2 items-start">
                                <x-heroicon-o-check class="flex-none text-green-600 translate-y-1 size-4" />
                                Bypass any adblocker.
                            </li>
                
                            <li class="flex gap-2 items-start">
                                <x-heroicon-o-check class="flex-none text-green-600 translate-y-1 size-4" />
                                Access to {{ Number::format($visitors) }} monthly developers.
                            </li>
                
                            <li class="flex gap-2 items-start">
                                <x-heroicon-o-check class="flex-none text-green-600 translate-y-1 size-4" />
                                A backlink on a DR 45-50 domain.
                            </li>
                        </ul>
                    </div>

                    <div class="md:w-1/2 w-full">
                        <div class="inline-flex items-baseline gap-2 text-xl">
                            <p class="text-7xl font-medium">
                                {{ Number::currency(400, 'EUR', precision: 0) }}
                            </p>

                            monthly
                        </div>
                    
                        <div class="flex flex-wrap md:flex-nowrap text-center md:text-left items-center gap-4 mt-6 justify-center md:justify-start">
                            <x-btn href="https://benjamincrozat.com/php-85" target="_blank">
                                Live example
                            </x-btn>
                            
                            <x-btn primary disabled>
                                Sold out!
                            </x-btn>
                        </div>
            
                        <p class="mt-6 md:mt-8 text-balance">Once done, <a href="mailto:hello@benjamincrozat.com" class="font-medium underline">email me</a> with the necessary information and you will be live within 24 hours.</p>
                    </div>
                </div>
            </x-section>
        </div>

        <div class="container xl:max-w-(--breakpoint-lg)">
            <x-section
                id="sticky-carousel"
                class="bg-white shadow-md shadow-black/10 ring-1 ring-black/10 rounded-xl p-4 md:p-8"
            >
                <x-typography.heading>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" class="size-16 mx-auto mb-2 -scale-y-100"><path d="M40 56h176v40H40z" opacity=".2"/><path d="M216 40H40c-8.836556 0-16 7.163444-16 16v144c0 8.836556 7.163444 16 16 16h176c8.836556 0 16-7.163444 16-16V56c0-8.836556-7.163444-16-16-16Zm0 16v40H40V56h176Zm0 144H40v-88h176v88Z"/></svg>
                    Secure a position in the sticky carousel
                </x-typography.heading>

                <div class="flex flex-wrap md:flex-nowrap text-center md:text-left items-start mt-8 justify-between gap-8 md:gap-16">
                    <div class="md:w-1/2 w-full">
                        <p class="font-medium">What you get:</p>
                        
                        <ul class="grid gap-2 mt-2 text-left">
                            <li class="flex gap-2 items-start">
                                <x-heroicon-o-check class="flex-none text-green-600 translate-y-1 size-4" />
                                Be visible in the sticky carousel on the bottom of the visitors' screen.
                            </li>

                            <li class="flex gap-2 items-start">
                                <x-heroicon-o-check class="flex-none text-green-600 translate-y-1 size-4" />
                                Access to {{ Number::format($visitors) }} monthly developers.
                            </li>

                            <li class="flex gap-2 items-start">
                                <x-heroicon-o-check class="flex-none text-green-600 translate-y-1 size-4" />
                                Bypass any adblocker.
                            </li>
                        </ul>
                    </div>

                    <div class="md:w-1/2 w-full">
                        <div class="inline-flex items-baseline gap-2 text-xl">
                            <p class="text-7xl font-medium">
                                {{ Number::currency(300, 'EUR', precision: 0) }}
                            </p>

                            monthly
                        </div>
                    
                        <div class="flex flex-wrap md:flex-nowrap text-center md:text-left items-center gap-4 mt-6 justify-center md:justify-start">
                            <x-btn
                                wire:navigate
                                href="https://benjamincrozat.com/php-85"
                                data-pirsch-event="Clicked sticky carousel demo"
                            >
                                Click + scroll to see it
                            </x-btn>

                            <x-btn
                                primary
                                href="{{ route('checkout.start', 'sticky_carousel') }}"
                                data-pirsch-event="Clicked sticky carousel checkout"
                                data-pirsch-meta-product="Sticky carousel"
                            >
                                Get started
                            </x-btn>
                        </div>
            
                        <p class="mt-6 md:mt-8 text-balance">Once done, <a href="mailto:hello@benjamincrozat.com" class="font-medium underline">email me</a> with the necessary information and you will be live within 24 hours.</p>
                    </div>
                </div>
            </x-section>
        </div>

        <div class="container xl:max-w-(--breakpoint-lg)">
            <x-section
                id="sponsored-article"
                class="bg-white shadow-md shadow-black/10 ring-1 ring-black/10 rounded-xl p-4 md:p-8"
            >
                <x-typography.heading>
                    <x-heroicon-o-newspaper class="size-16 mx-auto mb-2" />
                    Write a sponsored article
                </x-typography.heading>

                <div class="flex flex-wrap md:flex-nowrap text-center md:text-left items-start justify-between gap-8 md:gap-16 mt-8">
                    <div class="md:w-1/2 w-full">
                        <p class="font-medium">What you get:</p>
                        
                        <ul class="grid gap-2 mt-2 text-left">
                            <li class="flex gap-2 items-start">
                                <x-heroicon-o-check class="flex-none text-green-600 translate-y-1 size-4" />
                                Featured on top of every article from Monday to Sunday.
                            </li>
                
                            <li class="flex gap-2 items-start">
                                <x-heroicon-o-check class="flex-none text-green-600 translate-y-1 size-4" />
                                Access to {{ Number::format($visitors) }} monthly developers.
                            </li>
                
                            <li class="flex gap-2 items-start">
                                <x-heroicon-o-check class="flex-none text-green-600 translate-y-1 size-4" />
                                A backlink on a DR 45-50 domain.
                            </li>
                
                            <li class="flex gap-2 items-start">
                                <x-heroicon-o-check class="flex-none text-green-600 translate-y-1 size-4" />
                                <span>A secured position on the platform, <strong class="font-medium">forever</strong>.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="md:w-1/2 w-full">
                        <p class="text-7xl font-medium">
                            {{ Number::currency(500, 'EUR', precision: 0) }}
                        </p>
                    
                        <div class="flex flex-wrap md:flex-nowrap text-center md:text-left items-center gap-4 mt-6 justify-center md:justify-start">
                            <x-btn href="https://benjamincrozat.com/deploy-php-laravel-apps-sevalla" target="_blank">
                                Example sponsored article
                            </x-btn>
                            
                            <x-btn href="{{ route('checkout.start', 'sponsored_article') }}" primary>
                                Get started
                            </x-btn>
                        </div>
            
                        <p class="mt-6 md:mt-8 text-balance">Once done, <a href="mailto:hello@benjamincrozat.com" class="font-medium underline">email me</a> with your article. Here are the <a wire:navigate href="{{ route('advertise.guidelines') }}" class="font-medium underline">guidelines</a>. You will be live within 24 hours.</p>
                    </div>
                </div>
            </x-section>
        </div>
    </div>

    <x-section
        title="The past 30 days on benjamincrozat.com"
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

        <x-btn primary href="#products" class="mt-8 table mx-auto">
            Check offers
        </x-btn>
    </x-section>
</x-app>
