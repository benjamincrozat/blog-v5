{{--
Displays the components btn component and accepts component props, Blade attributes, and slot content.
--}}

@if ($attributes->has('href'))
<a
@else
<button
@endif
    {{
        $attributes
            ->except('no-wire-navigate')
            ->class([
                'inline-block disabled:bg-gray-100! disabled:hover:bg-gray-100! disabled:text-gray-300! font-medium rounded-xl text-center tracking-tight transition-colors',
                'bg-gray-200 hover:bg-gray-100' => $attributes->missing('primary') && $attributes->missing('primary-alt') && $attributes->missing('disabled'),
                'bg-blue-600 hover:bg-blue-500 text-white' => $attributes->has('primary'),
                'bg-blue-100 hover:bg-blue-50 text-blue-900' => $attributes->has('primary-alt'),
                'px-[1.3rem] py-[.65rem]' => ! $attributes->has('size'),
                'px-6 py-3 text-lg' => 'md' === $attributes->get('size'),
                'px-[.65rem] py-[.35rem] text-sm rounded-md' => 'sm' === $attributes->get('size'),
                'px-[.65rem] py-[.35rem] text-xs rounded-sm' => 'xs' === $attributes->get('size'),
            ])
            ->merge([
                'wire:navigate' => $attributes->has('href')
                    && $attributes->missing('no-wire-navigate')
                    && $attributes->missing('target')
                    && $attributes->missing('wire:navigate')
                    && \App\Support\InternalNavigation::shouldUseWireNavigate((string) $attributes->get('href')),
                'data-pirsch-event' => 'Clicked button',
                'data-pirsch-meta-value' => strip_tags($slot),
            ])
    }}
>
    {{ $slot }}
@if ($attributes->has('href'))
</a>
@else
</button>
@endif
