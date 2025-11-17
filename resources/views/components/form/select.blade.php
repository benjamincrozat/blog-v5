<div>
    @if (! empty($label))
        <label for="{{ $id }}" class="inline-block mb-2 font-medium">
            {{ $label }}@if (! empty($required))*@endif
        </label>
    @endif

    <select
        id="{{ $id }}"
        {{ $attributes->except(['id'])->class('w-full block px-3 py-2 rounded-md shadow-sm shadow-black/5 border border-gray-200 disabled:opacity-30 bg-white') }}
    >
        {{ $slot }}
    </select>

    @error($attributes->get('wire:model', $attributes->get('name')))
        <div class="mt-2 font-medium text-red-600">{{ $message }}</div>
    @enderror
</div>

