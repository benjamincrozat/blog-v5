<a wire:navigate href="{{ route('jobs.show', $job->slug) }}">
    <article class="p-6 rounded-xl flex flex-col ring-1 h-full shadow-md ring-black/10">
        <div class="grow">
            <div class="flex-none float-right size-12 rounded-full ring-1 ring-black/10 grid place-items-center">
                <x-heroicon-o-building-office class="text-gray-500 size-6" />
            </div>

            <p class="flex-none text-gray-400">
                {{ $job->created_at->diffForHumans() }}
            </p>

            <p class="text-sm mt-4 tracking-widest uppercase">
                {{ $job->company->name }}
            </p>

            <h1 class="mt-2 font-medium tracking-tight text-xl/tight">
                {{ $job->title }}
            </h1>
        </div>

        <div class="mt-3 flex items-center gap-6 justify-between">
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

            <x-btn primary class="rounded-full! pointer-events-none bg-gray-900">
                Details
            </x-btn>
        </div>
    </article>
</a>