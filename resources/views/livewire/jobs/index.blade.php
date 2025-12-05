<x-slot:title>
    Find your next software development job
</x-slot>

<div>
    @if ($jobs->currentPage() === 1)
        <header class="container">
            <x-typography.headline>
                Find your next software<br /> development job
            </x-typography.headline>
    
            <x-typography.subheadline class="mt-4 lg:mt-8">
                Remote for the travelers, hybrid for a better work-life balance, or on-site for the full experience.
            </x-typography.subheadline>
        </header>

        <div class="mt-12 lg:mt-20">
            <x-typography.heading>
                They're hiring
            </x-typography.heading>

            <div class="flex px-4 overflow-x-auto overflow-y-hidden items-center mt-2 justify-center gap-x-8 gap-y-4">
                <img src="https://d2i1lec1hyrmti.cloudfront.net/wp-content/themes/clouddev2020/cdev25/images/newblue.svg" alt="CloudDevs" class="h-7" />

                <img src="https://media.cmsmax.cloud/52pqMYw3f0h6sIityigBIf8T/cms-max-logo-horizontal.png" alt="CMS Max" class="h-5" />

                <img src="https://upload.wikimedia.org/wikipedia/en/d/d1/Hopper_Inc._Logo.png" alt="Hopper" class="h-14 translate-y-2" />

                <img src="https://r2.remoteok.com/jobs/356f231845e627c4ad7afc3801434ca01755705609.png" alt="Manifest" class="h-9 translate-y-0.5" />

                <img src="https://mms.businesswire.com/media/20240130740190/en/2014262/4/Metronome_Logo.jpg" alt="Metronome LLC" class="h-5 translate-y-0.5" />

                <img src="https://wpforms.com/wp-content/uploads/2023/09/WPForms-Logo-Dark.png" alt="wpforms" class="h-9" />

                <img src="https://wunderite.com/wp-content/uploads/2021/09/wunderite-logo-blue-text-1024x135.png" alt="Wunderite" class="h-5" />
            </div>
        </div>
    @endif
    
    <div class="grid container lg:grid-cols-12 mt-24 lg:mt-32">
        <div id="jobs" class="lg:col-span-9">
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
            class="lg:col-span-3 lg:ml-8 -order-1 lg:order-0"
            x-data="{ open: false }"
        >
            <div
                class="lg:block! lg:static fixed bottom-0 inset-x-0 bg-white shadow-xl ring-1 ring-black/10 rounded-xl p-4 lg:p-0 max-h-[50dvh] overflow-y-auto lg:overflow-y-visible lg:max-h-none lg:shadow-none lg:ring-0 lg:rounded-none pb-24 lg:pb-0"
                x-cloak
                x-show="open"
                x-trap="open"
                @click.away="open = false"
                @keydown.esc="open = false"
            >
                <x-typography.heading class="lg:text-left! mb-4 hidden lg:block">
                    Filters
                </x-typography.heading>
        
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
                class="lg:hidden! fixed lg:static bottom-4 left-1/2 -translate-x-1/2 size-16 ring-1 ring-black/10 rounded-full bg-white/75 backdrop-blur-md shadow-lg grid place-items-center"
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
