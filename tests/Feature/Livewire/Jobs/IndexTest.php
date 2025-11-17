<?php

use App\Models\Job;
use Livewire\Livewire;
use App\Enums\JobSetting;
use App\Enums\JobSeniority;
use App\Livewire\Jobs\Index;
use App\Enums\EmploymentStatus;

it('filters jobs by salary range and setting', function () {
    $matchingJob = Job::factory()->create([
        'title' => 'Fully Remote Senior Laravel Developer',
        'min_salary' => 80000,
        'max_salary' => 140000,
        'setting' => JobSetting::FullyRemote->value,
    ]);

    $excludedJob = Job::factory()->create([
        'title' => 'On-site Junior PHP Developer',
        'min_salary' => 40000,
        'max_salary' => 60000,
        'setting' => JobSetting::OnSite->value,
    ]);

    Livewire::test(Index::class)
        ->set('minSalary', '90000')
        ->set('maxSalary', '100000')
        ->set('setting', JobSetting::FullyRemote->value)
        ->assertSee($matchingJob->title)
        ->assertDontSee($excludedJob->title);
});

it('filters jobs by employment status, seniority, and equity', function () {
    $withEquity = Job::factory()->create([
        'title' => 'Senior Full-time Engineer With Equity',
        'employment_status' => EmploymentStatus::FullTime->value,
        'seniority' => JobSeniority::Senior->value,
        'equity' => true,
    ]);

    $withoutEquity = Job::factory()->create([
        'title' => 'Contract Junior Engineer Without Equity',
        'employment_status' => EmploymentStatus::Contract->value,
        'seniority' => JobSeniority::Junior->value,
        'equity' => false,
    ]);

    Livewire::test(Index::class)
        ->set('employmentStatus', EmploymentStatus::FullTime->value)
        ->set('seniority', JobSeniority::Senior->value)
        ->set('withEquity', true)
        ->assertSee($withEquity->title)
        ->assertDontSee($withoutEquity->title);
});

it('ignores invalid salary ranges', function () {
    $job = Job::factory()->create([
        'title' => 'Lead Platform Engineer',
        'min_salary' => 120000,
        'max_salary' => 150000,
    ]);

    Livewire::test(Index::class)
        ->set('minSalary', '130000')
        ->set('maxSalary', '100000') // Max lower than min should be ignored.
        ->assertSee($job->title);
});

it('resets pagination when filters change', function () {
    foreach (range(1, 15) as $index) {
        Job::factory()->create([
            'title' => 'Remote job ' . $index,
            'setting' => JobSetting::FullyRemote->value,
            'created_at' => now()->addMinutes($index),
        ]);
    }

    $firstPageTitle = 'Remote job 15';

    Livewire::test(Index::class)
        ->call('gotoPage', 2)
        ->assertDontSee($firstPageTitle)
        ->set('setting', JobSetting::FullyRemote->value)
        ->assertSee($firstPageTitle);
});
