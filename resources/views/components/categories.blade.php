{{--
Displays the components categories component and accepts component props, Blade attributes, and slot content.
--}}

@props(['categories'])

@if ($categories->isNotEmpty())
    <div {{ $attributes->class('flex gap-2') }}>
        {{ $slot }}

        @foreach ($categories->sortBy('name') as $category)
            <a
                wire:navigate
                href="{{ route('categories.show', $category->slug) }}"
                class="px-2 py-1 text-xs font-medium uppercase rounded-sm border border-gray-200 transition-colors hover:border-blue-300 hover:text-blue-600"
            >
                {{ $category->name }}
            </a>
        @endforeach
    </div>
@endif
