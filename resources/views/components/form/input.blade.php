<div>
    @if (! empty($label))
        <label for="{{ $id }}" class="inline-block mb-1 font-medium">
            {{ $label }}@if (! empty($required))*@endif
        </label>
    @endif

    <input
        type="{{ $type ?? 'text' }}"
        id="{{ $id }}"
        {{ $attributes->except(['id', 'type'])->class('w-full block px-3 py-2 placeholder-gray-300 rounded-md shadow-sm shadow-black/5 border border-gray-200 disabled:opacity-30') }}
    />

    @error($attributes->get('wire:model', $attributes->get('name')))
        <div class="mt-2 font-medium text-red-600">{{ $message }}</div>
    @enderror
</div>
