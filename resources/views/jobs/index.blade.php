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
    >
        @if ($jobs->isNotEmpty())
            <div class="grid lg:grid-cols-2 gap-4">
                @foreach ($jobs as $job)
                    <x-job :$job />
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500">
                There are no job offers at the moment.
            </p>
        @endif

        <x-pagination
            :paginator="$jobs"
            class="mt-16"
        />
    </x-section>
</x-app>
