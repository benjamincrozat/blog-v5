<x-app
    :hide-ad="true"
    title="Unlock the best tools for developers"
    description="Browse the great tools I gathered from across the web. Services and apps of all kinds to help you do your job more efficiently."
>
    <h1 class="px-4 font-medium tracking-tight text-center text-black text-4xl/none md:text-5xl lg:text-7xl text-balance">
        Discover the best tools<br class="hidden md:inline" />
        for developers
    </h1>

    <section class="mt-16 md:mt-24">
        <x-heading>
            Featured tools
        </x-heading>

        <p class="px-4 mt-2 leading-tight text-center">These companies are sponsoring my blog.<br class="hidden md:inline" /> Big thanks to them and make sure to check them out!</p>

        <div class="flex overflow-x-auto gap-8 px-4 mt-8 md:justify-center snap-mandatory snap-x">
            @php
            $components = collect([
                'ads.deals.coderabbit',
                'ads.deals.sevalla',
            ])->shuffle()->toArray();
            @endphp

            @foreach ($components as $component)
                <x-dynamic-component :$component class="w-[85%] md:w-[45%] lg:w-[45%] xl:w-[40%] snap-start scroll-ml-4 flex-none" />
            @endforeach
        </div>
    </section>

    <x-section title="Latest tools" class="mt-16 md:mt-24">
        <p class="px-4 -mt-6 leading-tight text-center">
            Browse the great tools I gathered from across the web.<br class="hidden md:inline" />
            Services and apps of all kinds to help you do your job more efficiently.
        </p>

        <div class="grid gap-8 mt-8 md:grid-cols-2">
            <x-tools.tinkerwell />
            <x-tools.tower />
            <x-tools.fathom-analytics />
            <x-tools.cloudways />
            <x-tools.mailcoach />
            <x-tools.wincher />
            <x-tools.uptimia />
            <x-tools.digitalocean />
        </div>
    </x-section>
</x-app>
