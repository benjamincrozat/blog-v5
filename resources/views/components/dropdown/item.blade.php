{{--
Renders the components dropdown item view.
--}}

@props([
    'icon' => null,
    'iconClass' => null,
    'description' => null,
])

@if ($attributes->has('href'))
<a
@else
<button
@endif
    {{ $attributes->class([
        'group flex w-full gap-2 px-4 py-2 transition-colors focus:outline-none hover:text-white',
        'items-start' => ! empty($description),
        'items-center' => empty($description),
        'focus:bg-blue-900/5 focus:text-blue-900 hover:bg-blue-600/75' => ! $attributes->has('destructive'),
        'hover:bg-red-600/75 focus:bg-red-600/10 text-red-600' => $attributes->has('destructive'),
    ])->merge([
        'data-pirsch-event' => "Clicked dropdown item",
        'data-pirsch-meta-value' => strip_tags($slot),
    ]) }}
>
    @if (! empty($icon))
        <x-dynamic-component :component="$icon" @class([
            'flex-none size-4',
            'mt-1' => ! empty($description),
            $iconClass,
        ]) />
    @endif

    <div>
        <div>
            {{ $slot }}
        </div>

        @if (! empty($description))
            <div class="mb-[.175rem] text-xs text-gray-500 transition-colors text-balance group-hover:text-white/75">
                {{ $description }}
            </div>
        @endif
    </div>
@if ($attributes->has('href'))
</a>
@else
</button>
@endif
