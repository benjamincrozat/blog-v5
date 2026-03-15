{{--
Wraps a dropdown trigger and panel, and accepts trigger and item slots plus Blade attributes for positioning.
--}}

<div
    {{ $attributes->merge([
        'x-data' => '{ open: false }',
        'x-id' => "['dropdown-trigger', 'dropdown-panel']",
    ]) }}
>
    <button {{ $btn->attributes->merge([
        '@click' => 'open = !open',
        'type' => $btn->attributes->get('type', 'button'),
        'aria-haspopup' => 'true',
        'x-bind:aria-expanded' => 'open.toString()',
        'x-bind:aria-controls' => '$id(\'dropdown-panel\')',
        'x-bind:id' => '$id(\'dropdown-trigger\')',
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
                    'role' => 'dialog',
                    'x-bind:id' => '$id(\'dropdown-panel\')',
                    'x-bind:aria-hidden' => '(! open).toString()',
                    'x-bind:aria-labelledby' => '$id(\'dropdown-trigger\')',
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
