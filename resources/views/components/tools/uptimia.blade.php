{{--
Presents the tools uptimia component UI and accepts component props, Blade attributes, and slot content.
--}}

<x-tools.item
    name="Uptimia"
    headline="Get alerts when your site is down"
    subheadline="Uptimia monitors your site’s uptime, speed, and SSL from 170+ global checkpoints."
    cta="Start free"
    cta-color="bg-[#009950]!"
    href="{{ route('merchants.show', 'uptimia') }}"
    :src="Vite::asset('resources/img/screenshots/uptimia.webp')"
/>
