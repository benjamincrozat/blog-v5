{{--
Displays the components posts list component and accepts component props, Blade attributes, and slot content.
--}}

@props(['posts'])

<ul {{ $attributes->class('grid gap-6') }}>
    @foreach ($posts as $post)
        <li>
            <x-compact-post :$post />
        </li>
    @endforeach
</ul>
