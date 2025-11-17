<x-ads.deals
    {{
        $attributes
            ->class('text-rose-900 bg-rose-50')
            ->merge([
                'href' => route('redirect-to-advertiser', [
                    'slug' => 'coderabbit',
                    'utm_source' => 'tools',
                ]),
                'name' => 'coderabbit',
                'svgLogo' => 'icon-coderabbit',
                'svgClass' => 'h-6',
                'descriptionColor' => 'text-rose-900',
                'description' => 'CodeRabbit helps your team review code faster and more effectively.',
                'cta' => 'Start free for 14 days',
                'ctaColor' => 'bg-orange-500!',
                'screenshot' => Vite::asset('resources/img/screenshots/coderabbit.webp'),
            ])
    }}
/>
