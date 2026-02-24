{{--
Displays the components jobs list component and accepts component props, Blade attributes, and slot content.
--}}

@props(['jobs'])

<ul {{ $attributes->class('grid gap-6') }}>
    @foreach ($jobs as $job)
        <li>
            <x-compact-job :$job />
        </li>
    @endforeach
</ul>
