{{--
Displays the components form index component and accepts component props, Blade attributes, and slot content.
--}}

<form {{ $attributes }}>
    @csrf

    @method($method ?? 'GET')

    {{ $slot }}
</form>
