{{--
Renders the components jobs list view.
--}}

@props(['jobs'])

<ul {{ $attributes->class('grid gap-6') }}>
    @foreach ($jobs as $job)
        <li>
            <x-compact-job :$job />
        </li>
    @endforeach
</ul>
