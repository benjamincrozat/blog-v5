@props([
    'canonical' => $canonical ?? url()->current(),
    'description' => 'The best blog about PHP, Laravel, AI, and every other topics involved in building software.',
    'image' => '',
    'title',
])

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />

        <title>{{ $title }}</title>

        <meta name="title" content="{{ $title }}" />
        <meta name="description" content="{{ $description }}" />

        <meta property="og:type" content="website" />
        <meta property="og:url" content="{{ url()->current() }}" />
        <meta property="og:title" content="{{ $title }}" />
        <meta property="og:description" content="{{ $description }}" />
        <meta property="og:image" content="{{ $image }}" />

        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:url" content="{{ url()->current() }}" />
        <meta name="twitter:title" content="{{ $title }}" />
        <meta name="twitter:description" content="{{ $description }}" />
        <meta name="twitter:image" content="{{ $image }}" />

        <livewire:styles />

        @vite('resources/css/app.css')

        <script
            defer
            src="https://api.pirsch.io/pa.js"
            id="pianjs"
            data-code="{{ 'production' === config('app.env') ? '5N2hIsUQsCVX1LQtvPdJ3AGwQZHGxtt5' : '2kktajcETdWwbGKEyt3Zi4SnhwxOVSY6' }}"
            data-disable-page-views
        ></script>

        <link
            rel="preload"
            as="style"
            href="https://fonts.googleapis.com/css2?family=Outfit:wght@200..800&display=swap"
            onload="this.onload=null;this.rel='stylesheet'"
        />

        <link rel="icon" type="image/png" href="{{ Vite::asset('resources/img/favicon-96x96.png') }}" sizes="96x96" />
        <link rel="icon" type="image/svg+xml" href="{{ Vite::asset('resources/img/favicon.svg') }}" />
        <link rel="shortcut icon" href="{{ Vite::asset('resources/img/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="180x180" href="{{ Vite::asset('resources/img/apple-touch-icon.png') }}" />

        <link rel="canonical" href="{{ $canonical }}" />

        <x-feed-links />
    </head>
    <body {{ $attributes->class('font-light text-gray-600') }} x-data>
        <div x-intersect:leave="$dispatch('showcase')"></div>

        <div class="flex flex-col min-h-screen">
            @if (app('impersonate')->isImpersonating())
                <div class="text-white bg-orange-600">
                    <p class="container p-4 text-center leading-[1.35] text-sm sm:text-base">
                        Currently impersonating {{ auth()->user()->name }}.
                        <a
                            href="{{ route('leave-impersonation') }}"
                            class="font-medium underline"
                        >
                            Return&nbsp;to&nbsp;account →
                        </a>
                    </p>
                </div>
            @endif

            @empty($hideAd)
                @if (random_int(0, 1))
                    <x-ads.top.sevalla />
                @else
                    <x-ads.top.coderabbit />
                @endif
            @endempty

            @empty($hideNavigation)
                <header class="container mt-4 xl:max-w-(--breakpoint-lg)">
                    <x-nav />
                </header>
            @endempty

            <main @class([
                'grow',
                'py-12 md:py-16' => empty($hideNavigation),
            ])>
                {{ $slot }}
            </main>

            @empty($hideFooter)
                <x-footer />
            @endempty
        </div>

        <x-status />

        <livewire:search />

        @empty($hideAd)
            @php
                $bottomAds = app()->isProduction() ? [] : Arr::shuffle([
                    [
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 560 560" class="size-8 flex-none"><g fill="none"><path fill="#FFF" d="M155 70h279v418H155z"/><path fill="#FA7216" d="M0 110C0 49.249 49.249 0 110 0h339.266c60.751 0 110 49.249 110 110v339.266c0 60.751-49.249 110-110 110H110c-60.751 0-110-49.249-110-110V110Z"/><g fill="#FFF"><path d="M157.294 182.778h59.469v38.117c0 8.496 0 27.095 15.353 27.095h110.29l50.14 50.86c.441.601 1.107 1.167 1.884 1.827 2.931 2.493 7.445 6.332 7.445 18.494v57.863h-59.469v-38.116c0-8.496 0-27.095-15.353-27.095H216.18l-49.558-50.86c-.441-.601-1.106-1.167-1.883-1.828-2.931-2.492-7.445-6.331-7.445-18.493v-57.864Z"/><path d="M216.763 116.878v65.9h125.934v-65.9H216.763Zm-.583 260.156v65.9h126.517v-65.9H216.18Z"/><path d="m342.697 182.778-.291 65.212h59.566l-.097-65.212h-59.178ZM157.294 311.823v65.211h58.886v-65.211h-58.886Z"/></g></g></svg>',
                        'title' => 'Some Company',
                        'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.',
                        'url' => 'https://example.com',
                        'cta' => 'Learn more',
                    ],
                    [
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 313.000305 313" class="size-8 flex-none"><g fill="none"><path fill="#D75D2C" d="M156.5003 313c86.432 0 156.5-70.067 156.5-156.5 0-86.4326-70.068-156.5-156.5-156.5C70.0674 0-.0000047 70.0674-.0000047 156.5-.0000047 242.933 70.0674 313 156.5003 313Z"/><path fill="#FEFEFE" d="M262.7733 130.577s-21.805-27.874-49.215-29.468c-17.688-1.044-21.973 1.319-22.741 3.079-1.098-9.1258-8.898-51.4055-62.784-60.3657 6.879 49.449 35.288 36.5643 52.019 70.6467 0 0-28.233-38.3749-74.649-24.2458 0 0 16.918 35.5168 66.958 42.7738 0 0 4.01 13.744 5.219 16.164 0 0-77.066-40.19-100.4661 36.945-17.4148-3.945-23.2561 14.96-3.2401 27.874 0 0 3.4058-13.525 11.699-17.538 0 0-17.797 19.848 3.1309 43.619h75.1143c1.815-3.007 9.848-18.825-10.019-30.798 14.024-.201 25.439 26.252 37.72 30.98h17.863c.604-1.469 1.868-5.867-1.1-9.824-4.574-5.247-14.589-4.537-14.5-14.24 3.459-45.139 71.143-31.277 68.991-85.602Z"/></g></svg>',
                        'title' => 'Another Company',
                        'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.',
                        'url' => 'https://example.com',
                        'cta' => 'Get started',
                    ],
                ]);
            @endphp

            <x-ads.bottom :ads="$bottomAds" />
        @endempty

        @livewireScriptConfig

        @vite('resources/js/app.js')
    </body>
</html>
