{{--
Displays the components links grid component and accepts component props, Blade attributes, and slot content.
--}}

@props(['links'])

<ul {{ $attributes->class('grid gap-10 gap-y-16 xl:gap-x-16 md:grid-cols-2 xl:grid-cols-3') }}>
    @foreach ($links as $link)
        <li>
            <x-link :$link />
        </li>
    @endforeach
</ul>
