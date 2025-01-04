@props(['headings'])

<ul>
    @foreach ($headings as $heading)
        <x-table-of-contents.item :$heading />
    @endforeach
</ul>
