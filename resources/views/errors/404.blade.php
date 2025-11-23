<x-app
    title="Whoops, this page doesn't exist."
    :hide-top-ad="true"
    :hide-navigation="true"
    :hide-footer="true"
>
    <div class="grid place-items-center min-h-screen md:text-xl">
        <div class="container md:max-w-(--breakpoint-sm)">
            <p class="text-7xl font-medium text-center">404</p>

            <h1 class="mt-2 text-3xl text-center md:text-5xl text-balance">
                Whoops, this page doesn't exist.
            </h1>

            <p class="mt-8">Possible causes:</p>

            <ul class="list-[circle] mt-1 list-inside ml-3">
                <li>I deleted the page, so this is expected.</li>
                <li>I messed up a redirect.</li>
                <li>I messed up a deployment.</li>
            </ul>

            <p class="mt-4">If you think something is wrong, I'd be grateful if you tell me.</p>

            <div class="flex gap-2 justify-center mt-10 text-base">
                <x-btn
                    href="mailto:hello@benjamincrozat.com"
                >
                    Email me
                </x-btn>

                <x-btn
                    primary
                    wire:navigate
                    href="{{ route('home') }}"
                >
                    Back to the homepage
                </x-btn>
            </div>
        </div>
    </div>
</x-app>
