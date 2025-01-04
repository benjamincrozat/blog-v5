@props(['heading'])

<li>
    <a href="#{{ $heading['slug'] }}">
        {{ $heading['text'] }}
    </a>

    @if (! empty($heading['children']))
        <x-table-of-contents :headings="$heading['children']" />
    @endif
</li>
