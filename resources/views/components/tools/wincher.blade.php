{{--
Renders the components tools wincher view.
--}}

<x-tools.item
    name="Wincher"
    headline="Rank higher on Google"
    subheadline="Use Wincher to track and grow your business’s search visibility. **Use WELCOME30 for 30% off your first invoice.**"
    cta="Start free"
    cta-color="bg-[#F09B4F]!"
    href="{{ route('merchants.show', 'wincher') }}"
    :src="Vite::asset('resources/img/screenshots/wincher.avif')"
/>
