<article class="rounded-xl p-1 flex flex-col ring-1 h-full shadow-md ring-black/10">
   <div class="grow rounded-xl p-3 md:p-5 bg-linear-to-b from-gray-900 bg-gray-800 text-white/75">
        <div class="flex-none float-right size-12 rounded-full ring-1 ring-white/50 grid place-items-center">
            <x-heroicon-o-building-office class="text-white size-6" />
        </div>

        <p class="flex-none text-white/75">
            {{ $job->created_at->diffForHumans() }}
        </p>

        <p class="text-sm mt-4 tracking-widest uppercase">
            {{ $job->company->name }}
        </p>

        <h1 class="mt-2 font-medium tracking-tight text-xl/tight">
            <a wire:navigate href="{{ route('jobs.show', $job->slug) }}">
                {{ $job->title }}
            </a>
        </h1>
    </div>

    <div class="p-3 pt-4 md:p-5 md:pt-6 flex items-center gap-6 justify-between">
        <div>
            <div class="flex items-center gap-2">
                @if ($job->min_salary && $job->max_salary)
                    <p><strong class="font-medium">{{ Number::currency($job->min_salary, $job->currency ?? 'USD', precision: 0) }}—{{ Number::currency($job->max_salary, $job->currency ?? 'USD', precision: 0) }}</strong></p>
                @else
                    <p>Salary is negotiable</p>

                    <x-help-btn class="translate-y-px">
                        <p>The salary was unspecified.</p>

                        <p class="mt-2">Some companies choose not to disclose a range upfront to allow more flexibility in negotiations (aka "save money").</p>
                        
                        <p class="mt-2 font-medium">Tip: aim higher than your ideal salary so their counter-offer lands close.</p>
                    </x-help-btn>
                @endif
            </div>

            @if ('fully-remote' === $job->setting)
                <p>Remote</p>
            @else
                <p>
                    @php $locations = collect($job->locations); @endphp

                    <span>{{ $locations->first() }}</span>

                    @if ($locations->count() > 1)
                        <span>(+ {{ $locations->count() - 1 }} more)</span>
                    @endif
                </p>
            @endif
        </div>

        <div class="flex items-center gap-2">
            @can('update', $job)
                <x-btn
                    href="{{ route('filament.admin.resources.jobs.edit', $job) }}"
                    class="rounded-full! bg-gray-200/75!"
                >
                    Edit
                </x-btn>
            @endcan

            <x-btn primary wire:navigate href="{{ route('jobs.show', $job) }}" class="rounded-full! bg-gray-900 hover:bg-gray-600">
                Details
            </x-btn>
        </div>
    </div>
</article>