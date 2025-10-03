<x-app
    :title="$jobListing->title"
    :description="$jobListing->description"
>
    <article class="container lg:max-w-(--breakpoint-md)">
        <h1 class="font-medium tracking-tight text-black text-balance text-3xl/none sm:text-4xl/none lg:text-5xl/none">
            {{ $jobListing->title }}
        </h1>

        <x-prose class="mt-8">
            <h2>About the job</h2>

            <table>
                <tr>
                    <th>Company</th>
                    <td>{{ $jobListing->company->name }}</td>
                </tr>

                @if ($jobListing->min_salary && $jobListing->max_salary)
                    <tr>
                        <th>Salary</th>
                        <td>{{ Number::currency($jobListing->min_salary, $jobListing->currency ?? 'USD') }}—{{ Number::currency($jobListing->max_salary, $jobListing->currency ?? 'USD') }}</td>
                    </tr>
                @endif

                <tr>
                    <th>Equity</th>
                    <td>{{ $jobListing->equity ? 'Yes' : 'No' }}</td>
                </tr>

                @if (!empty($jobListing->locations))
                    <tr>
                        <th>Locations</th>
                        <td>{{ collect($jobListing->locations)->join(', ') }}</td>
                    </tr>
                @endif

                <tr>
                    <th>Setting</th>
                    <td>{{ ucfirst($jobListing->setting) }}</td>
                </tr>
            </table>

            {!! Str::markdown($jobListing->description) !!}

            @if (! empty($jobListing->technologies))
                <h2>Technologies</h2>

                <ul>
                    @foreach ($jobListing->technologies as $technology)
                        <li>
                            {{ $technology }}
                        </li>
                    @endforeach
                </ul>
            @endif

            <h2>About {{ $jobListing->company->name }}</h2>

            {!! Str::markdown($jobListing->company->about) !!}

            @if (!empty($jobListing->perks))
                <h2>Perks and benefits</h2>

                <ul>
                    @foreach ($jobListing->perks as $perk)
                        <li>{{ $perk }}</li>
                    @endforeach
                </ul>
            @endif

            @if (!empty($jobListing->interview_process))
                <h2>Interview process</h2>

                <ul>
                    @foreach ($jobListing->interview_process as $step)
                        <li>{{ $step }}</li>
                    @endforeach
                </ul>
            @endif

            <h2>How to apply</h2>

            <ul>
                @foreach ($jobListing->how_to_apply as $step)
                    <li>{{ $step }}</li>
                @endforeach
            </ul>

            <div class="text-center not-prose">
                <x-btn primary href="{{ $jobListing->url }}" target="_blank">
                    Apply now
                </x-btn>
            </div>
        </x-prose>
    </article>

    @php
        $jobPosting = [
            '@context' => 'https://schema.org/',
            '@type' => 'JobPosting',
            'title' => $jobListing->title,
            'description' => $jobListing->description,
            'identifier' => [
                '@type' => 'PropertyValue',
                'name' => $jobListing->company->name,
                'value' => (string) $jobListing->id,
            ],
            'datePosted' => optional($jobListing->created_at)->toIso8601String(),
            'employmentType' => 'FULL_TIME',
            'hiringOrganization' => [
                '@type' => 'Organization',
                'name' => $jobListing->company->name,
            ],
            'directApply' => false,
        ];

        if ($jobListing->company->url) {
            $jobPosting['hiringOrganization']['sameAs'] = $jobListing->company->url;
        }

        if ($jobListing->company->logo) {
            $jobPosting['hiringOrganization']['logo'] = $jobListing->company->logo;
        }

        if ($jobListing->setting === 'fully-remote') {
            $jobPosting['jobLocationType'] = 'TELECOMMUTE';
        }

        if ($jobListing->min_salary && $jobListing->max_salary) {
            $jobPosting['baseSalary'] = [
                '@type' => 'MonetaryAmount',
                'currency' => $jobListing->currency ?? 'USD',
                'value' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => (int) $jobListing->min_salary,
                    'maxValue' => (int) $jobListing->max_salary,
                    'unitText' => 'YEAR',
                ],
            ];
        }

        if (!empty($jobListing->locations) && $jobListing->setting !== 'fully-remote') {
            $jobPosting['jobLocation'] = [
                '@type' => 'Place',
                'name' => collect($jobListing->locations)->first(),
            ];
        }
    @endphp

    <script type="application/ld+json">
        {!! json_encode($jobPosting, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
</x-app>
