<?php

namespace App\Actions;

use App\Models\Job;
use App\Models\User;
use App\Models\Company;
use App\Scraper\Webpage;
use App\Notifications\JobFetched;
use Illuminate\Support\Facades\DB;

class CreateJob
{
    public function create(Webpage $webpage, object $data) : Job
    {
        return DB::transaction(function () use ($webpage, $data) {
            $company = Company::query()->updateOrCreate([
                'name' => $data->company->name,
            ], [
                'url' => $data->company->url,
                'logo' => $data->company->logo,
                'about' => $data->company->about,
            ]);

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
                'locations' => $data->locations,
                'setting' => $data->setting,
                'employment_status' => $data->employment_status ?? null,
                'seniority' => $data->seniority ?? null,
                'min_salary' => $data->min_salary ?? 0,
                'max_salary' => $data->max_salary ?? 0,
                'currency' => $data->currency,
                'equity' => (bool) ($data->equity ?? false),
                'interview_process' => $data->interview_process ?? [],
            ]);

            User::query()
                ->where('github_login', 'benjamincrozat')
                ->first()
                ?->notify(new JobFetched($job));

            return $job;
        });
    }
}
