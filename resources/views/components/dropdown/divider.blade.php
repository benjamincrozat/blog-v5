{{--
Renders the components dropdown divider view.
--}}

<div {{ $attributes->class('not-first:h-px not-first:my-2 not-first:bg-black/10') }}></div>

@if ($slot->hasActualContent())
    <div class="px-4 py-2 text-xs font-bold tracking-widest uppercase">
        {{ $slot }}
    </div>
@endif
