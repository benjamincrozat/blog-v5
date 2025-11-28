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
    </div>

    <x-section 
        title="What you'll get, 100% for free"
        class="mt-16 max-w-(--breakpoint-sm)"
    >
        <ul class="grid gap-2 sm:text-lg -mt-4">
            <li><span class="text-green-600 mr-1">✓</span> No list of links</li>
            <li><span class="text-green-600 mr-1">✓</span> My takes, no generic content</li>
            <li><span class="text-green-600 mr-1">✓</span> Emails only when I have something to say</li>
            <li><span class="text-green-600 mr-1">✓</span> Discoveries and insights you can't easily find elsewhere</li>
        </ul>
    </x-section>

    <x-section 
        title="Join us, now"
        class="mt-16 md:max-w-(--breakpoint-sm)"
    >
        <x-form class="grid -mt-4">
            <x-form.input
                label="Your email"
                type="email"
                id="email"
                placeholder="you@example.com"
                required
            />

            <x-btn primary class="table mx-auto mt-4">
                Keep me posted
            </x-btn>
        </x-form>
    </x-section>
</div>
