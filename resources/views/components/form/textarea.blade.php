{{--
Displays the components form textarea component and accepts component props, Blade attributes, and slot content.
--}}

<div {{ $attributes->only('class') }}>
    @if (! empty($label))
        <label for="{{ $id }}" class="inline-block mb-2 font-medium">{{ $label }}@if (! empty($required))*@endif</label>
    @endif

    <textarea
        id="{{ $id }}"
        {{ $attributes->except(['class', 'id', 'type', 'value'])->class('w-full block px-3 py-2 placeholder-gray-300 rounded-md shadow-sm shadow-black/5 border border-gray-200 disabled:opacity-30 resize-none')->merge(['rows' => 1, 'x-autosize' => '']) }}
    >{{ $value ?? '' }}</textarea>

    @error($attributes->get('wire:model', $attributes->get('name')))
        <div class="mt-2 font-medium text-red-600">{{ $message }}</div>
    @enderror
</div>
