{{--
Presents the form index component UI and accepts component props, Blade attributes, and slot content.
--}}

<form {{ $attributes }}>
    @csrf

    @method($method ?? 'GET')

    {{ $slot }}
</form>
