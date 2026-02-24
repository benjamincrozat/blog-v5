{{--
Displays the components pagination component and accepts component props, Blade attributes, and slot content.
--}}

@props(['paginator'])

@if ($paginator->hasPages())
    <div {{ $attributes }}>
        {{ $paginator->links() }}
    </div>
@endif
