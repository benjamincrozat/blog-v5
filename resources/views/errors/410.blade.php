<x-app
    title="Whoops, this article doesn't exist anymore."
    :hide-top-ad="true"
    :hide-navigation="true"
    :hide-footer="true"
>
    <div class="grid place-items-center min-h-screen md:text-xl">
        <div class="container md:max-w-(--breakpoint-sm)">
            <p class="text-7xl font-medium text-center">410</p>

            <h1 class="mt-2 text-3xl text-center md:text-5xl text-balance">
                Whoops, this article doesn't exist anymore.
            </h1>

            <x-btn
                primary
                wire:navigate
                href="{{ route('home') }}"
                class="table mx-auto mt-16"
            >
                Back to the homepage
            </x-btn>
        </div>
    </div>
</x-app>
