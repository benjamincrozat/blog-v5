<x-slot:title>
    Survive the AI era as a developer
</x-slot>

<x-slot:hide-top-ad></x-slot>

<x-slot:hide-sticky-carousel></x-slot>

<div>
    <div class="container text-center">
        <h1 class="px-4 font-medium tracking-tight text-center text-black text-4xl/none md:text-5xl lg:text-7xl text-balance">
            Survive the AI era as a developer
        </h1>

        <p class="px-4 mt-5 text-balance tracking-tight text-black/75 text-xl md:text-2xl md:mt-8">
            Receive the best tips and tricks to stay ahead of the curve.
        </p>

        <div class="flex justify-center gap-4 mt-6.5 md:mt-10">
            <x-btn href="#about">
                Who the F are you?
            </x-btn>

            <x-btn primary href="#subscribe">
                Subscribe
            </x-btn>
        </div>
    </div>

    <x-section 
        title="What you'll get, 100% for free"
        class="mt-16 max-w-(--breakpoint-sm)"
    >
        <ul class="grid gap-2 -mt-4">
            <li>
                <span class="text-green-600 mr-1">✓</span>
                No list of links
            </li>

            <li>
                <span class="text-green-600 mr-1">✓</span>
                My takes, no generic content
            </li>

            <li>
                <span class="text-green-600 mr-1">✓</span>
                A "yes-reply" email if you want to discuss
            </li>

            <li>
                <span class="text-green-600 mr-1">✓</span>
                Emails only when I have something to say
            </li>

            <li>
                <span class="text-green-600 mr-1">✓</span>
                Discoveries and insights you can't easily find elsewhere
            </li>
        </ul>
    </x-section>

    <div class="container md:max-w-(--breakpoint-sm) mt-16">
        @if ($subscribed)
            <div
                x-init="new JSConfetti().addConfetti()"
                class="text-center text-lg/tight text-green-900"
            >
                <x-heroicon-o-check-circle class="mx-auto text-green-600 size-16" />

                <p class="mt-2">
                    Thanks for your interest!<br />
                    A confirmation email has been sent to your inbox.
                </p>
            </div>
        @else
            <div
                id="subscribe"
                class="scroll-mt-4"
            >
                <x-heading>
                    Join us, now
                </x-heading>
    
                <x-form wire:submit="subscribe" class="grid mt-4">
                    <x-form.input
                        label="Your email"
                        type="email"
                        id="email"
                        wire:model="email"
                        placeholder="you@example.com"
                        required
                    />
    
                    <x-btn primary class="table mx-auto mt-4">
                        Keep me posted
                    </x-btn>
                </x-form>
            </div>
        @endif
    </div>
    
    @if ($aboutUser)
        <div class="h-px my-16 bg-linear-to-r from-transparent via-gray-200 to-transparent"></div>

        <x-section title="About {{ $aboutUser->name }}" id="about" class="lg:max-w-(--breakpoint-md)">
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

    <script src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script>
</div>
