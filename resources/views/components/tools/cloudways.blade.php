{{--
Presents the tools cloudways component UI and accepts component props, Blade attributes, and slot content.
--}}

<x-tools.item
    name="Cloudways"
    headline="Easily deploy PHP web apps"
    cta="Start free"
    cta-color="bg-[#3641C2]!"
    href="{{ route('merchants.show', 'cloudways-php') }}"
    :src="Vite::asset('resources/img/screenshots/cloudways.webp')"
>
    <x-slot:subheadline>
PHP 8, scalability, Cloudflare, caching, 24/7 support, and more with Cloudways.
    </x-slot:subheadline>
</x-tools.item>
