{{--
Presents the pagination component UI and accepts component props, Blade attributes, and slot content.
--}}

@props(['paginator'])

@if ($paginator->hasPages())
    <div {{ $attributes }}>
        {{ $paginator->links() }}
    </div>
@endif
