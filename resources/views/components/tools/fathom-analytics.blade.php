{{--
Presents the tools fathom analytics component UI and accepts component props, Blade attributes, and slot content.
--}}

<x-tools.item
    name="Fathom Analytics"
    headline="Know who visits your site"
    subheadline="Fathom Analytics is a simple, privacy-focused web analytics. No cookies, ads, or tracking."
    cta="Start free + $10 off"
    cta-color="bg-[#171B18]!"
    href="{{ route('merchants.show', 'fathom-analytics') }}"
    :src="Vite::asset('resources/img/screenshots/fathom-analytics.webp')"
/>
