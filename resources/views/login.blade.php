<x-app
    :hide-top-ad="true"
    :hide-footer="true"
    :hide-navigation="true"
    title="Sign in"
>
    <div class="flex flex-col min-h-screen">
        <div class="container flex justify-between items-center mt-4">
            <div class="flex gap-3 items-center">
                <x-icon-logo class="h-9 fill-current" />

                <span class="hidden text-base font-bold tracking-widest uppercase md:inline">
                    benjamincrozat.com
                </span>
            </div>

            <a
                wire:navigate
                href="{{ route('home') }}"
                class="tracking-tight underline underline-offset-4 decoration-gray-600/30"
            >
                Back to the home page →
            </a>
        </div>

        <div class="grid place-items-center grow">
            <div class="container text-center lg:max-w-(--breakpoint-md)">
                <div class="text-lg font-bold text-black sm:text-2xl md:text-3xl">
                    To continue, please sign in
                </div>

                <div class="mt-2 text-gray-600 text-balance sm:text-lg md:text-xl">
                    By signing in, you will be able to use the comments section and share links with my {{ Number::format($visitors) }} monthly visitors.
                </div>

                <x-btn href="{{ route('auth.redirect') }}" primary class="mt-8">
                    Sign in with GitHub
                </x-btn>
            </div>
        </div>
    </div>
</x-app>
