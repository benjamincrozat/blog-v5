<x-app
    title="Community links"
    description="A collection of content created and shared by other web developers."
>
    <x-breadcrumbs class="container xl:max-w-screen-lg">
        <x-breadcrumbs.item>
            Links
        </x-breadcrumbs.item>
    </x-breadcrumbs>

    @if ($links->currentPage() === 1)
        <div class="container mt-16 mb-16 text-center text-black md:mb-32">
            <h1 class="font-bold tracking-tight text-black text-4xl/none md:text-5xl lg:text-7xl text-balance">
                <span class="text-blue-600">Keep learning</span> with the community
            </h1>

            @if ($distinctUserAvatars->isNotEmpty())
                <div class="flex items-center justify-center mt-8 md:mt-12">
                    @foreach ($distinctUserAvatars as $avatar)
                        <div class="-ml-2 overflow-hidden bg-white rounded-full">
                            <img src="{{ $avatar }}" class="size-8 md:size-10" />
                        </div>
                    @endforeach
                </div>

                <div class="mt-2 font-medium md:text-lg">
                    {{ trans_choice(':count developer|:count developers', $distinctUsersCount) }} submitted links
                </div>

                <x-btn
                    primary
                    size="md"
                    wire:navigate
                    href="{{ route('links.create') }}"
                    class="mt-8"
                >
                    Send a link
                </x-btn>
            @endif
        </div>
    @endif

    <x-section :title="$links->currentPage() > 1 ? 'Page ' . $links->currentPage() : 'Latest links'">
        @if ($links->isNotEmpty())
            <ul class="grid gap-10 mt-8 gap-y-16 xl:gap-x-16 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($links as $link)
                    <li>
                        <x-link :$link />
                    </li>
                @endforeach
            </ul>
        @endif

        @if ($links->hasPages())
            <div class="mt-16">
                {{ $links->links() }}
            </div>
        @endif
    </x-section>
</x-app>
