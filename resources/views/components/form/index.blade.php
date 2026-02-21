{{--
Renders the components form index view.
--}}

<form {{ $attributes }}>
    @csrf

    @method($method ?? 'GET')

    {{ $slot }}
</form>
