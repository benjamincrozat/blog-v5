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

                @if (!empty($job->locations))
                    <tr>
                        <th class="text-right align-top py-2 pr-2 md:py-4 md:pr-4">Locations</th>
                        <td class="py-2 md:py-4 text-balance pl-2 md:pl-4">{{ collect($job->locations)->join(', ') }}</td>
                    </tr>
                @endif

                <tr>
                    <th class="text-right align-top py-2 pr-2 md:py-4 md:pr-4">Setting</th>
                    <td class="py-2 md:py-4 pl-2 md:pl-4">{{ ucfirst($job->setting) }}</td>
                </tr>
            </table>

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

            <h2>About {{ $job->company->name }}</h2>

            {!! Markdown::parse($job->company->about) !!}

            <div class="text-center not-prose">
                <x-btn primary href="{{ $job->url }}" target="_blank">
                    Apply now
                </x-btn>
            </div>
        </x-prose>
    </article>

    <script type="application/ld+json">
        {!! json_encode($jobPostingSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
</x-app>
