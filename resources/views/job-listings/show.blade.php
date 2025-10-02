<x-app
    :title="$jobListing->title"
    :description="$jobListing->description"
>
    @push('head')
        @php
            $jobPosting = [
                '@context' => 'https://schema.org/',
                '@type' => 'JobPosting',
                'title' => $jobListing->title,
                'description' => strip_tags(Str::markdown($jobListing->description)),
                'identifier' => [
                    '@type' => 'PropertyValue',
                    'name' => $jobListing->company->name,
                    'value' => (string) $jobListing->id,
                ],
                'datePosted' => optional($jobListing->created_at)->toIso8601String(),
                'employmentType' => 'FULL_TIME',
                'hiringOrganization' => array_filter([
                    '@type' => 'Organization',
                    'name' => $jobListing->company->name,
                    'sameAs' => $jobListing->company->url,
                    'logo' => $jobListing->company->logo,
                ]),
                'directApply' => true,
            ];

            if ($jobListing->updated_at) {
                $jobPosting['dateModified'] = $jobListing->updated_at->toIso8601String();
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

            if (! empty($jobListing->locations)) {
                $jobPosting['jobLocation'] = collect($jobListing->locations)
                    ->map(function (string $loc) {
                        return [
                            '@type' => 'Place',
                            'address' => [
                                '@type' => 'PostalAddress',
                                'addressLocality' => Str::of($loc)->before(',')->trim()->value(),
                                'addressCountry' => Str::of($loc)->after(',')->trim()->value(),
                            ],
                        ];
                    })
                    ->values()
                    ->all();
            }

            if ($jobListing->setting === 'fully-remote') {
                $jobPosting['jobLocationType'] = 'TELECOMMUTE';
            }
        @endphp
        <script type="application/ld+json">{!! json_encode($jobPosting, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endpush
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

            <h2>Technologies</h2>

            <ul>
                @foreach ($jobListing->technologies as $technology)
                    <li>
                        {{ $technology }}
                    </li>
                @endforeach
            </ul>

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
</x-app>
