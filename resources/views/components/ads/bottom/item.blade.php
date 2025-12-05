@php
$domain = preg_replace('/https?:\/\//', '', config('app.url'));
@endphp

<a
    {{
        $attributes
            ->class('flex items-start gap-4 sm:gap-6 basis-full shrink-0 snap-center px-4 pb-4 sm:px-6')
            ->merge([
                'x-bind:href' => 'ad.url',
                'x-bind:target' => "ad.url.includes('$domain') ? null : '_blank'",
                'x-bind:data-ad-index' => 'index',
                'x-on:click' => 'trackAdClick(ad)',
                'x-intersect.once' => 'trackAdView(ad, index)',
            ])
    }}
>
    <div x-html="ad.icon" class="mt-1 flex-none"></div>

    <div class="grid grow gap-2 leading-tight">
        <h1 class="font-semibold text-balance text-black/95" x-html="ad.title"></h1>
        <div x-text="ad.description"></div>
        <x-btn 
            primary-alt 
            size="sm" 
            class="pointer-events-none mt-[.55rem] bg-blue-200/50" 
            x-html="`${ad.cta}&nbsp;→`"
        ></x-btn>
    </div>
</a>