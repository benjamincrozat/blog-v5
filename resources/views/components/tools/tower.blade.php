{{--
Displays the components tools tower component and accepts component props, Blade attributes, and slot content.
--}}

<x-tools.item
    name="Tower"
    headline="Unlock the power of Git"
    subheadline="Tower is an easy-to-use and powerful Git client for Mac and Windows."
    cta="Start free"
    cta-color="bg-[#FDCB18]!"
    cta-text-color="text-yellow-950!"
    href="{{ route('merchants.show', 'tower') }}"
    :src="Vite::asset('resources/img/screenshots/tower.webp')"
/>
