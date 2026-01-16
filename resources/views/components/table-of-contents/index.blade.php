@props(['items'])

@if (! empty($items))
    <div {{ $attributes->class('px-4 py-6 mt-4 ml-0 rounded-lg bg-gray-50') }}>
        <x-typography.heading tag="h2" class="text-sm">
            Table of contents
        </x-typography.heading>

        <x-table-of-contents.items :$items class="mt-4 ml-0" />
    </div>
@endif
