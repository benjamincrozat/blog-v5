<x-slot:title>
    Survive the AI era as a developer
</x-slot>

<x-slot:description>
    Practical tips and insights to stay ahead as a web developer, delivered by email.
</x-slot:description>

<x-slot:hide-top-ad></x-slot>

<x-slot:hide-sticky-carousel></x-slot>

<div>
    <div class="container">
        <x-typography.headline>
            Survive the AI era as a developer
        </x-typography.headline>

        <x-typography.subheadline class="mt-6 md:mt-10">
            Receive the best tips and tricks to stay ahead of the curve.
        </x-typography.subheadline>

        <div class="sm:max-w-[480px] mx-auto mt-8 md:mt-10">
            <x-form wire:submit="subscribe" class="grid mt-4">
                <x-honeypot livewire-model="honeypot" />

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
