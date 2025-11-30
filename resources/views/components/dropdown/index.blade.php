<div
    {{ $attributes->merge([
        'x-data' => '{ open: false }',
    ]) }}
>
    <button {{ $btn->attributes->merge([
        '@click' => 'open = !open',
        'data-pirsch-event' => 'Clicked dropdown button',
        'data-pirsch-meta-value' => strip_tags($btn),
    ]) }}>
        {{ $btn }}
    </button>

    <div
        {{
            $items
                ->attributes
                ->class('z-10 py-2 text-base bg-white/75 backdrop-blur-md rounded-lg shadow-lg ring-1 ring-black/10 min-w-[240px] max-w-[360px]')
                ->merge([
                    'x-anchor.bottom' => '$el.previousElementSibling',
                    'x-cloak' => true,
                    'x-show' => 'open',
                    'x-trap' => 'open',
                    '@keydown.esc' => 'open = false',
                    '@keydown.arrow-down.stop.prevent' => '$focus.next()',
                    '@keydown.arrow-up.stop.prevent' => '$focus.prev()',
                    '@click.away' => 'open = false',
                ])
        }}
    >
        {{ $items }}
    </div>
</div>
