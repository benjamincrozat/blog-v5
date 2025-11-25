<div {{ $attributes->merge([
    'x-data' => '{ open: false }',
    'x-trap' => 'open',
    '@click.away' => 'open = false',
    '@keydown.esc' => 'open = false',
]) }}>
    <button
        @click="open = !open"
        data-pirsch-event="Clicked help button"
    >
        <x-heroicon-o-question-mark-circle class="size-[1em] translate-y-[.125rem] opacity-75" />
        <span class="sr-only">What is this?</span>
    </button>

    <div
        class="bg-white/75 text-wrap backdrop-blur-md normal-case font-light tracking-normal py-3 px-4 rounded-xl ring-1 ring-black/10 shadow-lg min-w-[360px]"
        x-anchor="$el.parentElement"
        x-cloak
        x-show="open"
        x-transition
        @click.away="open = false"
    >
        {{ $slot }}
    </div>
</div>
