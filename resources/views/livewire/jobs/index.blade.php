<x-slot:title>
    The latest job offers for developers
</x-slot>

<div>
    <div class="container text-center mb-16 md:mb-24">
        <h1 class="px-4 font-medium tracking-tight text-center text-black text-4xl/none md:text-5xl lg:text-7xl text-balance">
            AI won't replace you.<br />
            Apply to jobs.
        </h1>

        <p class="text-xl text-balance md:text-2xl mt-8 md:mt-12">
            I gathered <strong class="font-medium text-black">{{ $recentJobsCount }} new job offers</strong> during the last 7 days.
        </p>

        <div class="flex gap-4 justify-center mt-8 md:mt-12">
            <x-btn href="#jobs">
                Browse jobs
            </x-btn>

            {{-- <x-btn primary>
                Maximize your chances
            </x-btn> --}}
        </div>
    </div>
    
    <div class="grid container md:grid-cols-12 md:gap-8">
        <div id="jobs" class="md:col-span-9">
            <x-heading class="mb-8">
                <span class="text-blue-600">{{ $recentJobsCount }} new job offers</span> in the last 7 days
            </x-heading>

            @if ($this->hasActiveFilters())
                <ul class="flex flex-wrap gap-2 mb-8">
                    @if ($query)
                        <li class="bg-blue-600 flex items-center gap-2 text-white rounded-full px-[.85rem] py-[.35rem] font-medium cursor-default">
                            {{ $query }}

                            <button wire:click="$set('query', '')" class="flex-none mr-[-.125rem]">
                                <x-heroicon-o-x-mark class="size-4" />
                                <span class="sr-only">Clear</span>
                            </button>
                        </li>
                    @endif

                    @if ($minSalary)
                        <li class="bg-blue-600 flex items-center gap-2 text-blue-50 rounded-full px-[.85rem] py-[.35rem]">
                            <div>
                                Minimum salary: <strong class="font-medium text-white">{{ Number::currency($minSalary, 'USD', precision: 0) }}</strong>
                            </div>

                            <button wire:click="$set('minSalary', '')" class="flex-none mr-[-.125rem]">
                                <x-heroicon-o-x-mark class="size-4" />
                                <span class="sr-only">Clear</span>
                            </button>
                        </li>
                    @endif

                    @if ($maxSalary)
                        <li class="bg-blue-600 flex items-center gap-2 text-blue-50 rounded-full px-[.85rem] py-[.35rem]">
                            <div>
                                Maximum salary: <strong class="font-medium text-white">{{ Number::currency($maxSalary, 'USD', precision: 0) }}</strong>
                            </div>

                            <button wire:click="$set('maxSalary', '')" class="flex-none mr-[-.125rem]">
                                <x-heroicon-o-x-mark class="size-4" />
                                <span class="sr-only">Clear</span>
                            </button>
                        </li>
                    @endif

                    @if ($setting)
                        <li class="bg-blue-600 flex items-center gap-2 text-white rounded-full px-[.85rem] py-[.35rem] font-medium cursor-default">
                            {{ ucfirst($setting) }}

                            <button wire:click="$set('setting', '')" class="flex-none mr-[-.125rem]">
                                <x-heroicon-o-x-mark class="size-4" />
                                <span class="sr-only">Clear</span>
                            </button>
                        </li>
                    @endif

                    @if ($employmentStatus)
                        <li class="bg-blue-600 flex items-center gap-2 text-white rounded-full px-[.85rem] py-[.35rem] font-medium cursor-default">
                            {{ ucfirst($employmentStatus) }}

                            <button wire:click="$set('employmentStatus', '')" class="flex-none mr-[-.125rem]">
                                <x-heroicon-o-x-mark class="size-4" />
                                <span class="sr-only">Clear</span>
                            </button>
                        </li>
                    @endif
                    
                    @if ($seniority)
                        <li class="bg-blue-600 flex items-center gap-2 text-white rounded-full px-[.85rem] py-[.35rem] font-medium cursor-default">
                            {{ ucfirst($seniority) }}

                            <button wire:click="$set('seniority', '')" class="flex-none mr-[-.125rem]">
                                <x-heroicon-o-x-mark class="size-4" />
                                <span class="sr-only">Clear</span>
                            </button>
                        </li>
                    @endif

                    @if ($withEquity)
                        <li class="bg-blue-600 flex items-center gap-2 text-white rounded-full px-[.85rem] py-[.35rem] font-medium cursor-default">
                            With equity

                            <button wire:click="$set('withEquity', false)" class="flex-none mr-[-.125rem]">
                                <x-heroicon-o-x-mark class="size-4" />
                                <span class="sr-only">Clear</span>
                            </button>
                        </li>
                    @endif

                    <li>
                        <button wire:click="clearFilters" class="border border-transparent py-[.35rem] ml-1 font-medium transition-colors hover:text-blue-600">
                            Clear all filters
                        </button>
                    </li>
                </ul>
            @endif

            <x-pagination
                :paginator="$jobs"
                class="mb-8"
            />

            @if ($jobs->isNotEmpty())
                <div class="grid mt-8 md:grid-cols-2 gap-4">
                    @foreach ($jobs as $job)
                        <x-job :$job />
                    @endforeach
                </div>
            @else
                <p class="text-center mt-8 text-gray-500">
                    @if ($this->hasActiveFilters())
                        No results found for your filters.
                    @else
                        There are no job offers at the moment.
                    @endif
                </p>
            @endif

            <x-pagination
                :paginator="$jobs"
                class="mt-8 md:mt-16"
            />
        </div>
    
        <div
            class="md:col-span-3 -order-1 md:order-0"
            x-data="{ open: false }"
        >
            <div
                class="md:block! md:static fixed bottom-0 inset-x-0 bg-white shadow-xl ring-1 ring-black/10 rounded-xl p-4 md:p-0 max-h-[50dvh] overflow-y-auto md:overflow-y-visible md:max-h-none md:shadow-none md:ring-0 md:rounded-none pb-24 md:pb-0"
                x-cloak
                x-show="open"
                x-trap="open"
                @click.away="open = false"
                @keydown.esc="open = false"
            >
                <x-heading class="md:text-left! mb-4 hidden md:block">
                    Filters
                </x-heading>
        
                @php($activeFilterClasses = 'border-blue-300! shadow-blue-100! text-blue-600')
        
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
                                'flex items-center gap-[.65rem] font-medium transition-colors',
                                'text-blue-600' => $withEquity,
                            ])
                        >
                            <input
                                id="with-equity"
                                type="checkbox"
                                wire:model.live="withEquity"
                                class="rounded border-gray-200 shadow shadow-black/5"
                            />
        
                            <span>Only show jobs with equity</span>
                        </label>
                    </div>
                </div>
            </div>
    
            <button
                class="md:hidden! fixed md:static bottom-4 left-1/2 -translate-x-1/2 size-16 ring-1 ring-black/10 rounded-full bg-white/75 backdrop-blur-md shadow-lg grid place-items-center"
                @click="open = !open"
            >
                <x-heroicon-o-adjustments-vertical
                    class="size-8"
                    x-show="!open"
                />
    
                <x-heroicon-o-x-mark
                    class="size-8"
                    x-cloak
                    x-show="open"
                />
                
                <span class="sr-only">Filters</span>
            </button>
        </div>
    </div>
</div>
