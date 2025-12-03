@props([
    'name',
    'description',
    'cta',
    'svgLogo',
    'svgClass',
    'screenshot',
    'descriptionColor',
    'ctaColor',
])

<div
    {{
        $attributes
            ->class('flex rounded-xl overflow-hidden transition-opacity hover:opacity-50')
            ->merge([
                'x-intersect.once' => $user?->isAdmin() ? null : "pirsch(`Deal shown`, {
                    meta: { name: `$name` }
                })"
            ])
    }}
>
    <div class="flex flex-col flex-1 p-4 md:p-6">
        <x-dynamic-component :component="$svgLogo" class="self-start {{ $svgClass }}" />

        <p class="grow mt-4 sm:text-balance {{ $descriptionColor }}">
            {{ $description }}
        </p>

        <div class="flex items-center mt-8 gap-8">
            @if (! empty($reviewUrl))
                <a href="{{ $reviewUrl }}" class="font-medium text-black/85">
                    My review
                </a>
            @endif
    
            <x-btn
                primary
                href="{{ route('redirect-to-advertiser', [
                    'slug' => $name,
                    'utm_source' => 'tools',
                ]) }}"
                target="_blank"
                class="cursor-pointer rounded-md! {{ $ctaColor }}"
            >
                {{ $cta }}
            </x-btn>
        </div>
    </div>

    <div class="relative flex-none w-[20%] sm:w-[33.33%] lg:flex-1">
        <img
            loading="lazy"
            src="{{ $screenshot }}"
            alt="{{ $name }}"
            class="object-cover absolute inset-0 w-full h-full ring-1 shadow-2xl ring-black/10 object-top-left"
        />
    </div>
</div>
