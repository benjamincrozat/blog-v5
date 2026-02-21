{{--
Renders the components tools tinkerwell view.
--}}

<x-tools.item
    name="Tinkerwell"
    headline="Prototype and debug on the fly"
    cta="Get started"
    cta-color="bg-[#4470D4]!"
    href="{{ route('merchants.show', 'tinkerwell') }}"
    :src="Vite::asset('resources/img/screenshots/tinkerwell.webp')"
>
    <x-slot:subheadline>
Tinkerwell lets you code and debug your PHP, Laravel, Symfony, WordPress, etc., apps in an editor designed for fast feedback and quick iterations.
    </x-slot:subheadline>
</x-tools.item>
