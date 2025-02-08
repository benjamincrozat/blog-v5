@props(['headings'])

<ul {{ $attributes }}>
    @foreach ($headings as $heading)
        <x-table-of-contents.item :$heading />
    @endforeach
</ul>
