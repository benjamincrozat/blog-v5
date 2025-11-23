@props(['job'])

<div {{ $attributes->class('flex items-center gap-4 md:gap-6') }}>
    <div class="flex-none size-12 rounded-full ring-1 ring-black/10 grid place-items-center">
        <x-heroicon-o-building-office class="text-gray-500 size-6" />
    </div>

    <div class="grow">
        <div class="flex gap-4 md:gap-6 justify-between">
            <p class="text-gray-500 line-clamp-1">
                {{ $job->company->name }}
            </p>

            <p class="text-gray-500 flex-none">
                {{ $job->created_at->diffForHumans() }}
            </p>
        </div>

        <p class="mt-1">
            <a
                wire:navigate
                href="{{ route('jobs.show', $job->slug) }}"
                class="font-bold transition-colors hover:text-blue-600"
                data-pirsch-event="Clicked job"
                data-pirsch-meta-title="{{ $job->title }}"
            >
                {{ $job->title }}
            </a>
        </p>

        <p class="flex items-center gap-2 mt-1 font-medium">
            <x-heroicon-o-banknotes class="size-4 opacity-75" />
            {{ Number::currency($job->min_salary, $job->currency ?? 'USD') }}—{{ Number::currency($job->max_salary, $job->currency ?? 'USD') }}
        </p>
    </div>
</div>
