<a
    {{
        $attributes
            ->class('flex items-start gap-4 sm:gap-6 basis-full shrink-0 snap-center sm:py-4 p-4 sm:px-6')
            ->merge([
                'target' => '_blank',
                'x-bind:href' => 'ad.url',
                'x-bind:data-ad-index' => 'index',
            ])
    }}
>
    <div x-html="ad.icon" class="mt-1"></div>

    <div class="leading-tight">
        <h1 class="font-semibold text-black/95" x-text="ad.title"></h1>
        
        <p class="text-black/75">
            <span x-text="ad.description"></span>
            <span class="font-medium underline" x-text="`${ad.cta} →`"></span>
        </p>
    </div>
</a>