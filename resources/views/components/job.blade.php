<article class="p-6 flex items-stretch gap-6 rounded-xl ring-1 shadow-md ring-black/10">
    <div class="flex-none size-12 rounded-full ring-1 ring-black/10 grid place-items-center">
        <x-heroicon-o-building-office class="text-gray-500 size-6" />
    </div>

    <div class="grow flex flex-col">
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
                <a wire:navigate href="{{ route('jobs.show', $job->slug) }}">
                    {{ $job->title }}
                </a>
            </h1>
    
            @if (($locations = collect($job->locations))->isNotEmpty())
                <p class="flex flex-wrap gap-2 items-center mt-4 leading-none">
                    {!! $locations->take(3)->join(' <span class="opacity-50 text-xs/none">/</span> ') !!}
    
                    @if ($locations->count() > 3)
                        <span class="opacity-50 text-xs/none">/</span>
                        
                        <a wire:navigate href="{{ route('jobs.show', $job) }}" class="underline">
                            {{ $locations->count() - 3 }} more →
                        </a>
                    @endif
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
                    @foreach (collect($job->technologies)->take(7) as $technology)
                        <li class="flex gap-2 items-center">
                            <x-heroicon-o-tag class="text-gray-500 size-4" />
                            {{ $technology }}
                        </li>
                    @endforeach
    
                    @if (($technologies = collect($job->technologies))->count() > 7)
                        <li class="flex gap-2 items-center">
                            <a href="{{ route('jobs.show', $job) }}" class="underline">{{ $technologies->count() - 7 }} more →</a>
                        </li>
                    @endif
                </ul>
            @endif
        </div>

        <p class="mt-4">
            <span class="text-gray-400">Source:</span> {{ $job->source }}
        </p>
    </div>
</article>