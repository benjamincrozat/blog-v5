<x-app
    title="The latest job offers for developers"
>
    @if ($jobs->currentPage() === 1)
        <div class="container text-center mb-24">
            <div class="font-medium tracking-tight text-black text-4xl/none md:text-5xl lg:text-7xl text-balance">
                <span class="text-blue-600">{{ trans_choice(':count new job|:count new jobs', $recentJobsCount) }}</span> in the last 30 days
            </div>

            <div class="mt-4 text-balance tracking-tight text-black/75 text-lg/tight sm:text-xl/tight md:text-2xl/tight">
                I gather job offers across the web and you apply. Deal?
            </div>

            <x-btn
                primary
                size="md"
                href="#jobs"
                class="mt-7 md:mt-11"
            >
                Start applying
            </x-btn>
        </div>
    @endif

    <x-section
        :title="$jobs->currentPage() > 1
            ? 'Page ' . $jobs->currentPage()
            : 'Latest job offers'"
        :big-title="$jobs->currentPage() === 1"
        id="jobs"
        class="xl:max-w-(--breakpoint-lg)"
    >
        @if ($jobs->isNotEmpty())
            <div class="grid gap-4">
                @foreach ($jobs as $job)
                    <a wire:navigate href="{{ route('jobs.show', $job) }}" target="_blank">
                        <article class="p-6 flex items-start gap-6 rounded-xl ring-1 shadow-md ring-black/10">
                            <div class="flex-none size-12 rounded-full ring-1 ring-black/10 grid place-items-center">
                                <x-heroicon-o-building-office class="text-gray-500 size-6" />
                            </div>

                            <div class="grow">
                                <div class="flex gap-16 justify-between items-start">
                                    <p class="text-sm tracking-widest uppercase">
                                        {{ $job->company->name }}
                                    </p>

                                    <p class="flex-none text-gray-500">
                                        {{ $job->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                <h1 class="mt-2 font-medium tracking-tight max-w-2/3 text-xl/tight">
                                    {{ $job->title }}
                                </h1>

                                @if (!empty($job->locations))
                                    <p class="flex flex-wrap gap-2 items-center mt-4 leading-none">
                                        {!! collect($job->locations)->join(' <span class="opacity-50 text-xs/none">/</span> ') !!}
                                    </p>
                                @endif

                                <p class="flex flex-wrap gap-2 items-center mt-3 leading-none">
                                    {{ ucfirst($job->setting) }}

                                    @if ($job->min_salary && $job->max_salary)
                                        <span class="opacity-50 text-xs/none">/</span>

                                        {{ Number::currency($job->min_salary, $job->currency ?? 'USD') }}—{{ Number::currency($job->max_salary, $job->currency ?? 'USD') }}
                                    @endif
                                </p>

                                @if (! empty($job->technologies))
                                    <ul class="flex flex-wrap gap-y-1 gap-x-5 items-center mt-4">
                                        @foreach ($job->technologies as $technology)
                                            <li class="flex gap-2 items-center">
                                                <x-heroicon-o-tag class="text-gray-500 size-4" />
                                                {{ $technology }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                <p class="mt-4">
                                    <span class="text-gray-400">Source:</span> {{ $job->source }}
                                </p>
                            </div>
                        </article>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500">
                There is no job offers at the moment.
            </p>
        @endif

        <x-pagination
            :paginator="$jobs"
            class="mt-16"
        />
    </x-section>
</x-app>
