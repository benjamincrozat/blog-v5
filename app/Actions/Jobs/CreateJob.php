<?php

namespace App\Actions\Jobs;

use App\Models\Job;
use App\Models\User;
use App\Models\Company;
use App\Models\Location;
use App\Scraper\Webpage;
use Illuminate\Support\Str;
use App\Notifications\JobFetched;
use Illuminate\Support\Facades\DB;
use App\Actions\NormalizeCompanyUrl;
use App\Actions\NormalizeCompanyDomain;

class CreateJob
{
    public function create(Webpage $webpage, object $data) : Job
    {
        return DB::transaction(function () use ($webpage, $data) {
            $company = $this->upsertCompany($data);

            $job = Job::query()->updateOrCreate([
                'url' => $data->url,
            ], [
                'html' => $webpage->content,
                'company_id' => $company->id,
                'source' => $data->source,
                'language' => $data->language,
                'title' => $data->title,
                'description' => $data->description,
                'technologies' => $data->technologies,
                'perks' => $data->perks ?? [],
                'setting' => $data->setting,
                'employment_status' => $data->employment_status ?? null,
                'seniority' => $data->seniority ?? null,
                'min_salary' => $data->min_salary ?? 0,
                'max_salary' => $data->max_salary ?? 0,
                'currency' => $data->currency,
                'equity' => (bool) ($data->equity ?? false),
                'interview_process' => $data->interview_process ?? [],
            ]);

            $job->locations()->sync($this->resolveLocationIds($data));

            User::query()
                ->where('github_login', 'benjamincrozat')
                ->first()
                ?->notify(new JobFetched($job));

            return $job;
        });
    }

    /**
     * @return array<int>
     */
    protected function resolveLocationIds(object $data) : array
    {
        return collect($data->location_entities ?? [])
            ->filter(fn ($entry) => null !== data_get($entry, 'country'))
            ->map(function ($entry) {
                return Location::query()->firstOrCreate([
                    'city' => data_get($entry, 'city'),
                    'region' => data_get($entry, 'region'),
                    'country' => data_get($entry, 'country'),
                ])->id;
            })
            ->values()
            ->all();
    }

    protected function upsertCompany(object $data) : Company
    {
        $normalizedUrl = app(NormalizeCompanyUrl::class)->handle($data->company->url ?? null);

        $normalizedDomain = app(NormalizeCompanyDomain::class)->handle($normalizedUrl ?? $data->company->url ?? null);

        $slug = Str::slug($data->company->name);

        $company = Company::query()
            ->where('domain', $normalizedDomain)
            ->orWhere('slug', $slug)
            ->first();

        if (! $company) {
            $company = new Company;
            $company->slug = $slug;
        }

        $company->fill([
            'name' => $data->company->name,
            'url' => $normalizedUrl ?? $data->company->url,
            'domain' => $normalizedDomain,
            'logo' => $data->company->logo,
            'about' => $data->company->about,
        ]);

        if (! $company->slug) {
            $company->slug = $slug;
        }

        $company->save();

        return $company;
    }
}
