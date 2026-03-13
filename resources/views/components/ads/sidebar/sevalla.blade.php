{{--
Displays the components ads sidebar sevalla component and accepts component props, Blade attributes, and slot content.
--}}

<a
    {{
        $attributes
            ->class('grid gap-4 p-4 !pt-6 leading-tight bg-orange-50/75 rounded-xl text-orange-900')
            ->merge([
                'href' => route('redirect-to-advertiser', [
                    'slug' => 'sevalla',
                    'utm_medium' => 'sidebar',
                ]),
                'target' => '_blank'
            ])
    }}
>
    <img
        loading="lazy"
        src="https://www.gravatar.com/avatar/d58b99650fe5d74abeb9d9dad5da55ad?s=256"
        alt="Benjamin Crozat"
        width="40"
        height="40"
        class="mx-auto h-10 rounded-full"
    />

    <p class="-mt-2 leading-tight text-center"><strong class="font-semibold text-orange-900">“Successful developers automate”</strong></p>

    <p>Don't waste time on server maintenance. Let Sevalla do it for you.</p>

    <ul class="grid gap-1 -mt-2 ml-3 list-disc list-inside">
        <li>PHP and Laravel-friendly</li>
        <li>Multiple environments</li>
        <li>Scheduled tasks and jobs</li>
        <li>Built-in security</li>
        <li><strong class="font-medium text-orange-950">Get $50 free credits</strong></li>
    </ul>

    <img
        loading="lazy"
        src="{{ Vite::asset('resources/img/screenshots/sevalla.webp') }}"
        width="1280"
        height="720"
        alt="Sevalla's interface"
        class="mt-5 rounded ring-1 shadow-lg transition-transform scale-125 rotate-1 hover:rotate-0 hover:scale-150 shadow-orange-900/10 ring-orange-900/10"
    />

    <x-btn primary class="w-full bg-orange-500! hover:bg-orange-400! mt-6 text-center !rounded-md cursor-pointer">
        Claim $50 free credits
    </x-btn>
</a>
