{{--
Wraps full-page Livewire routes in the shared site shell and accepts an optional title slot.
--}}

@props([
    'title' => config('app.name'),
])

<x-app :title="$title">
    {{ $slot }}
</x-app>
