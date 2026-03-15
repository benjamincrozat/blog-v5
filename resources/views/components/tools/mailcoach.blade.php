{{--
Presents the tools mailcoach component UI and accepts component props, Blade attributes, and slot content.
--}}

<x-tools.item
    name="Mailcoach"
    headline="Send emails to your users"
    subheadline="Self-hosted email marketing built for Laravel developers, by Laravel developers."
    cta="Start free"
    cta-color="bg-[#142C6E]!"
    href="{{ route('merchants.show', 'mailcoach') }}"
    :src="Vite::asset('resources/img/screenshots/mailcoach.webp')"
/>
