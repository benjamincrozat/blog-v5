{{--
Renders the components pagination view.
--}}

@props(['paginator'])

@if ($paginator->hasPages())
    <div {{ $attributes }}>
        {{ $paginator->links() }}
    </div>
@endif
