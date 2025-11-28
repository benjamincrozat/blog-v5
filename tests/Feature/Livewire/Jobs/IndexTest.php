<?php

use App\Models\Job;
use Livewire\Livewire;
use App\Enums\JobSetting;
use App\Enums\JobSeniority;
use App\Livewire\Jobs\Index;
use App\Enums\EmploymentStatus;

class TestableJobsIndex extends Index
{
    public bool $pageReset = false;

    public function setPublicProperty(string $property, $value) : void
    {
        $this->{$property} = $value;
    }

    public function normalize(string $property) : void
    {
        $this->normalizeEmptyProperty($property);
    }

    public function salaryBoundsPublic() : array
    {
        return $this->salaryBounds();
    }

    public function normalizeSalaryPublic(?string $value) : ?int
    {
        return $this->normalizeSalary($value);
    }

    public function hasActiveFiltersPublic() : bool
    {
        return $this->hasActiveFilters();
    }

    public function isSearchingPublic() : bool
    {
        return $this->isSearching();
    }

    public function resetPage($pageName = 'page') : void
    {
        $this->pageReset = true;
    }
}

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

it('clears all filters at once', function () {
    Livewire::test(Index::class)
        ->set('query', 'laravel')
        ->set('minSalary', '50000')
        ->set('maxSalary', '90000')
        ->set('setting', JobSetting::Hybrid->value)
        ->set('employmentStatus', EmploymentStatus::Contract->value)
        ->set('seniority', JobSeniority::Senior->value)
        ->set('withEquity', true)
        ->call('clearFilters')
        ->assertSet('query', null)
        ->assertSet('minSalary', null)
        ->assertSet('maxSalary', null)
        ->assertSet('setting', null)
        ->assertSet('employmentStatus', null)
        ->assertSet('seniority', null)
        ->assertSet('withEquity', false);
});

it('normalizes empty query-string backed properties', function () {
    $component = new TestableJobsIndex;
    $component->setPublicProperty('query', '');
    $component->normalize('query');
    expect($component->query)->toBeNull();

    $component->setPublicProperty('minSalary', '');
    $component->normalize('minSalary');
    expect($component->minSalary)->toBeNull();

    // Properties outside the allow-list remain untouched.
    $component->setPublicProperty('withEquity', '');
    $component->normalize('withEquity');
    expect($component->withEquity)->toBeFalse();
});

it('normalizes salary inputs and ignores invalid ranges', function () {
    $component = new TestableJobsIndex;

    expect($component->normalizeSalaryPublic(null))->toBeNull();
    expect($component->normalizeSalaryPublic(''))->toBeNull();
    expect($component->normalizeSalaryPublic('0'))->toBeNull();
    expect($component->normalizeSalaryPublic('-10'))->toBeNull();
    expect($component->normalizeSalaryPublic('75000'))->toBe(75000);

    $component->setPublicProperty('minSalary', '90000');
    $component->setPublicProperty('maxSalary', '80000'); // flipped range.

    [$min, $max] = $component->salaryBoundsPublic();

    expect($min)->toBe(90000);
    expect($max)->toBeNull();
});

it('detects active filters and search mode', function () {
    $component = new TestableJobsIndex;

    expect($component->hasActiveFiltersPublic())->toBeFalse();

    $component->setPublicProperty('withEquity', true);
    expect($component->hasActiveFiltersPublic())->toBeTrue();

    $component->setPublicProperty('query', 'a');
    expect($component->isSearchingPublic())->toBeFalse();

    $component->setPublicProperty('query', 'laravel');
    expect($component->isSearchingPublic())->toBeTrue();
});

it('ignores pagination updates when the page property changes', function () {
    $component = new TestableJobsIndex;
    $component->updated('page');

    expect($component->pageReset)->toBeFalse();
});
