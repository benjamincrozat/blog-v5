<x-slot:title>
    The latest job offers for developers
</x-slot>

<div class="grid container md:grid-cols-12 gap-12 md:gap-8">
    <section id="jobs" class="md:col-span-9">
        <x-heading class="md:text-left!">
            @if ($jobs->currentPage() > 1 && ! empty($query))
                Results for "{{ $query }}" (Page {{ $jobs->currentPage() }})
            @elseif ($jobs->currentPage() > 1 && empty($query))
                Page {{ $jobs->currentPage() }}
            @elseif (! empty($query))
                Results for "{{ $query }}"
            @else
                <span class="text-blue-600">{{ trans_choice(':count new job|:count new jobs', $recentJobsCount) }}</span> in the last 30 days
            @endif
        </x-heading>

        @if ($jobs->isNotEmpty())
            <div class="grid mt-4 md:grid-cols-2 gap-4">
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
    </section>

    <div class="md:col-span-3 -order-1 md:order-0">
        <div class="flex justify-between items-baseline mb-4">
            <x-heading>
                Filters
            </x-heading>

            @if ($hasActiveFilters)
                <button
                    type="button"
                    wire:click="clearFilters"
                    class="text-blue-600 font-medium"
                >
                    Clear filters
                </button>
            @endif
        </div>

        @php($activeFilterClasses = 'border-blue-300! shadow-blue-100!')

        <div class="grid gap-4">
            <x-form.input 
                label="Search" 
                id="query" 
                wire:model.live.debounce.500ms="query"
                placeholder="Type “PHP”, “New York City”, etc."
                :class="filled($query) ? $activeFilterClasses : null"
            />

            <x-form.input 
                label="Minimum salary"
                type="number"
                min="0"
                step="500"
                id="min-salary" 
                wire:model.live.debounce.500ms="minSalary"
                placeholder="45000"
                :class="filled($minSalary) ? $activeFilterClasses : null"
            />

            <x-form.input 
                label="Maximum salary"
                type="number"
                min="0"
                step="500"
                id="max-salary" 
                wire:model.live.debounce.500ms="maxSalary"
                placeholder="240000"
                :class="filled($maxSalary) ? $activeFilterClasses : null"
            />

            <x-form.select
                label="Setting"
                id="setting"
                wire:model.live="setting"
                :class="filled($setting) ? $activeFilterClasses : null"
            >
                <option value="">Any setting</option>

                @foreach ($settingOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </x-form.select>

            <x-form.select
                label="Employment status"
                id="employment-status"
                wire:model.live="employmentStatus"
                :class="filled($employmentStatus) ? $activeFilterClasses : null"
            >
                <option value="">Any status</option>

                @foreach ($employmentStatusOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </x-form.select>

            <x-form.select
                label="Seniority"
                id="seniority"
                wire:model.live="seniority"
                :class="filled($seniority) ? $activeFilterClasses : null"
            >
                <option value="">Any level</option>

                @foreach ($seniorityOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </x-form.select>

            <div>
                <label
                    for="with-equity"
                    @class([
                        'flex items-center gap-3 font-medium cursor-pointer select-none transition-colors',
                        'text-blue-600' => $withEquity,
                    ])
                >
                    <input
                        id="with-equity"
                        type="checkbox"
                        wire:model.live="withEquity"
                        class="size-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                    />

                    <span>Only show jobs with equity</span>
                </label>
            </div>
        </div>
    </div>
</div>
