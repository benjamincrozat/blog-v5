{{--
Renders the components tools digitalocean view.
--}}

<x-tools.item
    name="DigitalOcean"
    headline="Host your web apps on a VPS"
    subheadline="DigitalOcean provides affordable, scalable, and reliable VPS hosting."
    cta="Start with $200 free credit"
    href="{{ route('merchants.show', 'digitalocean') }}"
    :src="Vite::asset('resources/img/screenshots/digitalocean.webp')"
/>
