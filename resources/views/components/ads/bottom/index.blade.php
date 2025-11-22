@props([
    'ads',
])

<div
    {{
        $attributes
            ->class('fixed bottom-2 group-hover inset-x-2 sm:right-auto sm:left-1/2 sm:-translate-x-1/2 bg-black/75 group backdrop-blur-md rounded-md shadow-xl sm:py-4 p-4 sm:px-6 sm:w-[480px] text-white/75 backdrop-saturate-200')
    }}
    x-cloak
    x-data="{
        show: false,
    }"
    x-show="show"
    x-transition.duration.600ms
    @showcase.window="show = true"
>
    <div class="flex items-center gap-4 sm:gap-6">
        <x-heroicon-s-academic-cap class="size-8 flex-none" />

        <div class="leading-tight">
            <h1 class="font-semibold text-white" x-text="$ad['title']"></h1>
            <p class="line-clamp-2" x-text="$ad['description']"></p>
        </div>
    </div>
</div>