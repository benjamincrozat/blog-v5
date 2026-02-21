{{--
Renders the components posts list view.
--}}

@props(['posts'])

<ul {{ $attributes->class('grid gap-6') }}>
    @foreach ($posts as $post)
        <li>
            <x-compact-post :$post />
        </li>
    @endforeach
</ul>
