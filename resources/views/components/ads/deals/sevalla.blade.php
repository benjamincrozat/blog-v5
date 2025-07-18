<x-ads.deals
    {{
        $attributes
            ->class('bg-orange-50')
            ->merge([
                'href' => route('redirect-to-advertiser', [
                    'slug' => 'sevalla',
                    'utm_source' => 'deals',
                ]),
                'name' => 'Sevalla',
                'svgLogo' => 'icon-sevalla',
                'descriptionColor' => 'text-orange-700',
                'description' => 'Deploy and scale your web applications securely with ease.',
                'cta' => '$50 free credits',
                'ctaColor' => 'bg-[#FA7216]!',
                'screenshot' => Vite::asset('resources/img/screenshots/sevalla.webp'),
            ])
    }}
/>
