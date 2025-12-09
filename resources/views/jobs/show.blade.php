<x-app
    :title="$job->title"
    :description="$job->description"
>
    <article class="container lg:max-w-(--breakpoint-md)">
        <h1 class="font-medium tracking-tight text-black text-balance text-3xl/none sm:text-4xl/none lg:text-5xl/none">
            {{ $job->title }}
        </h1>

        <x-prose class="mt-8">
            <h2>About the job</h2>

            <table class="!w-auto">
                <tr>
                    <th class="text-right align-top py-2 pr-2 md:py-4 md:pr-4">Added</th>
                    <td class="py-2 md:py-4 pl-2 md:pl-4">{{ $job->created_at->diffForHumans() }}</td>
                </tr>

                <tr>
                    <th class="text-right align-top py-2 pr-2 md:py-4 md:pr-4">Company</th>
                    <td class="py-2 md:py-4 pl-2 md:pl-4">{{ $job->company->name }}</td>
                </tr>

                @if ($job->min_salary && $job->max_salary)
                    <tr>
                        <th class="text-right align-top py-2 pr-2 md:py-4 md:pr-4">Salary</th>
                        <td class="py-2 md:py-4 pl-2 md:pl-4">{{ Number::currency($job->min_salary, $job->currency ?? 'USD', precision: 0) }}—{{ Number::currency($job->max_salary, $job->currency ?? 'USD', precision: 0) }}</td>
                    </tr>
                @endif

                @if ($job->employment_status)
                    <tr>
                        <th class="text-right align-top py-2 pr-2 md:py-4 md:pr-4">
                            Status
                        </th>
                        
                        <td class="py-2 md:py-4 pl-2 md:pl-4">
                            {{ ucfirst($job->employment_status) }}
                        </td>
                    </tr>
                @endif

                @if ($job->seniority)
                    <tr>
                        <th class="text-right align-top py-2 pr-2 md:py-4 md:pr-4">
                            Seniority
                        </th>
                        
                        <td class="py-2 md:py-4 pl-2 md:pl-4">
                            {{ ucfirst($job->seniority) }}
                        </td>
                    </tr>
                @endif

                <tr>
                    <th class="text-right align-top py-2 pr-2 md:py-4 md:pr-4">Equity</th>
                    <td class="py-2 md:py-4 pl-2 md:pl-4">{{ $job->equity ? 'Yes' : 'No' }}</td>
                </tr>

                @php $locations = $job->locations; @endphp

                @if ($locations->isNotEmpty())
                    <tr>
                        <th class="text-right align-top py-2 pr-2 md:py-4 md:pr-4">Location</th>
                        <td class="py-2 md:py-4 text-balance pl-2 md:pl-4">
                            <span>{{ $locations->first()->display_name }}</span>

                            @if ($locations->count() > 1)
                                <span>(+ {{ $locations->count() - 1 }} more)</span>
                            @endif
                        </td>
                    </tr>
                @endif

                <tr>
                    <th class="text-right align-top py-2 pr-2 md:py-4 md:pr-4">Setting</th>
                    <td class="py-2 md:py-4 pl-2 md:pl-4">{{ ucfirst($job->setting) }}</td>
                </tr>
            </table>

            <h2>Description summary</h2>

            {!! Markdown::parse($job->description) !!}

            @if (! empty($job->technologies))
                <h2>Technologies</h2>

                <ul>
                    @php
                    $technologies = $job->technologies;
                    sort($technologies, SORT_NATURAL | SORT_FLAG_CASE);
                    @endphp

                    @foreach ($technologies as $technology)
                        <li>
                            {{ $technology }}
                        </li>
                    @endforeach
                </ul>
            @endif

            @if (!empty($job->perks))
                <h2>Perks and benefits</h2>

                <ul>
                    @foreach ($job->perks as $perk)
                        <li>{!! Markdown::parse($perk) !!}</li>
                    @endforeach
                </ul>
            @endif

            @if (!empty($job->interview_process))
                <h2>Interview process</h2>

                <ul>
                    @foreach ($job->interview_process as $step)
                        <li>{!! Markdown::parse($step) !!}</li>
                    @endforeach
                </ul>
            @endif

            <h2>What you need to know about {{ $job->company->name }}, the company</h2>

            {!! Markdown::parse($job->company->about) !!}

            <div class="text-center not-prose">
                <x-btn primary href="{{ $job->url }}" target="_blank">
                    Apply now
                </x-btn>
            </div>

            {{-- @if (! empty($job->recommendedJobs))
                <h2>Increase your chances, apply to more jobs</h2>

                <div class="not-prose">
                    <ul class="grid gap-6">
                        @foreach ($job->recommendedJobs as $recommendedJob)
                            <li class="grid gap-1">
                                <div class="flex items-baseline gap-8">
                                    <div>
                                        <p>
                                            <a 
                                                wire:navigate 
                                                href="{{ route('jobs.show', $recommendedJob) }}"
                                                class="font-medium underline"
                                            >
                                                {{ $recommendedJob->title }} →
                                            </a>
                                        </p>
                                        
                                        <p>
                                            <strong class="font-medium">Why you should apply:</strong> {{ $recommendedJob->reason }}
                                        </p>
                                    </div>

                                    <p class="flex-none text-gray-500">{{ $recommendedJob->created_at->diffForHumans(short: true) }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif --}}
        </x-prose>
    </article>

    <script type="application/ld+json">
        {!! json_encode($jobPostingSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
</x-app>
