<?php

namespace App\Livewire\Jobs;

use App\Models\Job;
use Livewire\Component;
use App\Enums\JobSetting;
use Illuminate\View\View;
use App\Enums\JobSeniority;
use Illuminate\Support\Arr;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Enums\EmploymentStatus;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class Index extends Component
{
    use WithPagination;

    /**
     * Search text stored in the query string.
     */
    #[Url(history: true, keep: false)]
    public ?string $query = null;

    /**
     * Salary filters are strings to preserve the query-string values.
     */
    #[Url(history: true, keep: false)]
    public ?string $minSalary = null;

    #[Url(history: true, keep: false)]
    public ?string $maxSalary = null;

    /**
     * Filters represented as raw strings but validated against enums.
     */
    #[Url(history: true, keep: false)]
    public ?string $setting = null;

    #[Url(history: true, keep: false)]
    public ?string $employmentStatus = null;

    #[Url(history: true, keep: false)]
    public ?string $seniority = null;

    #[Url(history: true, keep: false)]
    public bool $withEquity = false;

    public function render() : View
    {
        return view('livewire.jobs.index', [
            'logos' => Arr::shuffle([
                [
                    'src' => 'https://d2i1lec1hyrmti.cloudfront.net/wp-content/themes/clouddev2020/cdev25/images/newblue.svg',
                    'alt' => 'CloudDevs',
                    'class' => 'h-7 translate-y-0.5',
                ],
                [
                    'src' => 'https://media.cmsmax.cloud/52pqMYw3f0h6sIityigBIf8T/cms-max-logo-horizontal.png',
                    'alt' => 'CMS Max',
                    'class' => 'h-5 translate-y-0.5',
                ],
                [
                    'src' => 'https://upload.wikimedia.org/wikipedia/en/d/d1/Hopper_Inc._Logo.png',
                    'alt' => 'Hopper',
                    'class' => 'h-14 translate-y-2',
                ],
                [
                    'src' => 'https://r2.remoteok.com/jobs/356f231845e627c4ad7afc3801434ca01755705609.png',
                    'alt' => 'Manifest',
                    'class' => 'h-9 translate-y-0.5',
                ],
                [
                    'src' => 'https://mms.businesswire.com/media/20240130740190/en/2014262/4/Metronome_Logo.jpg',
                    'alt' => 'Metronome LLC',
                    'class' => 'h-4.5 translate-y-0.5',
                ],
                [
                    'src' => 'https://wpforms.com/wp-content/uploads/2023/09/WPForms-Logo-Dark.png',
                    'alt' => 'wpforms',
                    'class' => 'h-9',
                ],
                [
                    'src' => 'https://wunderite.com/wp-content/uploads/2021/09/wunderite-logo-blue-text-1024x135.png',
                    'alt' => 'Wunderite',
                    'class' => 'h-5',
                ],
            ]),
            'jobs' => $this->paginateJobs(),
            'settingOptions' => JobSetting::options(),
            'employmentStatusOptions' => EmploymentStatus::options(),
            'seniorityOptions' => JobSeniority::options(),
            'hasActiveFilters' => $this->hasActiveFilters(),
        ]);
    }

    public function updated(string $propertyName) : void
    {
        if ('page' === $propertyName) {
            return;
        }

        // When fields are cleared in the UI, Livewire keeps an empty string; convert it back to null.
        $this->normalizeEmptyProperty($propertyName);

        // Validate the specific property so users get instant feedback if a value is invalid.
        if (array_key_exists($propertyName, $this->rules())) {
            $this->validateOnly($propertyName);
        }

        $this->resetPage();
    }

    public function clearFilters() : void
    {
        $this->reset([
            'query',
            'minSalary',
            'maxSalary',
            'setting',
            'employmentStatus',
            'seniority',
            'withEquity',
        ]);

        $this->resetPage();
    }

    /**
     * Livewire validation rules defined in one place for readability.
     */
    protected function rules() : array
    {
        return [
            'query' => ['nullable', 'string', 'min:1'],
            'minSalary' => ['nullable', 'numeric', 'min:0'],
            'maxSalary' => ['nullable', 'numeric', 'min:0'],
            'setting' => ['nullable', Rule::in(JobSetting::values())],
            'employmentStatus' => ['nullable', Rule::in(EmploymentStatus::values())],
            'seniority' => ['nullable', Rule::in(JobSeniority::values())],
            'withEquity' => ['boolean'],
        ];
    }

    /**
     * Build the paginator by reusing the same filter logic for Scout and SQL.
     */
    protected function paginateJobs() : LengthAwarePaginator
    {
        if ($this->isSearching()) {
            // Use Scout whenever the query string is long enough, but still layer filters on the SQL builder.
            return Job::search($this->query)
                ->query(fn (Builder $builder) => $this->applyFilters($builder))
                ->paginate(20);
        }

        return $this->applyFilters(
            Job::query()->latest()
        )->paginate(20);
    }

    /**
     * Apply the filters one by one for clarity.
     */
    protected function applyFilters(Builder $builder) : Builder
    {
        [$minSalary, $maxSalary] = $this->salaryBounds();

        if (null !== $minSalary) {
            // Match listings whose maximum salary reaches the floor or have only a minimum well above it.
            $builder->where(function (Builder $salaryQuery) use ($minSalary) : void {
                $salaryQuery
                    ->where('max_salary', '>=', $minSalary)
                    ->orWhere(function (Builder $alternateQuery) use ($minSalary) : void {
                        $alternateQuery
                            ->whereNull('max_salary')
                            ->whereNotNull('min_salary')
                            ->where('min_salary', '>=', $minSalary);
                    });
            });
        }

        if (null !== $maxSalary) {
            // Match listings whose minimum salary sits below the ceiling or have only a maximum under it.
            $builder->where(function (Builder $salaryQuery) use ($maxSalary) : void {
                $salaryQuery
                    ->where('min_salary', '<=', $maxSalary)
                    ->orWhere(function (Builder $alternateQuery) use ($maxSalary) : void {
                        $alternateQuery
                            ->whereNull('min_salary')
                            ->whereNotNull('max_salary')
                            ->where('max_salary', '<=', $maxSalary);
                    });
            });
        }

        if ($this->setting) {
            $builder->where('setting', $this->setting);
        }

        if ($this->employmentStatus) {
            $builder->where('employment_status', $this->employmentStatus);
        }

        if ($this->seniority) {
            $builder->where('seniority', $this->seniority);
        }

        if ($this->withEquity) {
            $builder->where('equity', true);
        }

        return $builder;
    }

    /**
     * @return array{0: ?int, 1: ?int}
     */
    protected function salaryBounds() : array
    {
        $minSalary = $this->normalizeSalary($this->minSalary);
        $maxSalary = $this->normalizeSalary($this->maxSalary);

        // Ignore the upper bound if the user accidentally flips the range.
        if (null !== $minSalary && null !== $maxSalary && $maxSalary < $minSalary) {
            $maxSalary = null;
        }

        return [$minSalary, $maxSalary];
    }

    protected function normalizeSalary(?string $value) : ?int
    {
        if (null === $value || '' === $value) {
            return null;
        }

        // Livewire sends numeric inputs as strings, so cast before validating the semantics.
        $number = (int) $value;

        if ($number <= 0) {
            return null;
        }

        return $number;
    }

    /**
     * Livewire stores query-string values as strings, so we clear empty ones.
     */
    protected function normalizeEmptyProperty(string $propertyName) : void
    {
        if (! in_array($propertyName, [
            'query',
            'minSalary',
            'maxSalary',
            'setting',
            'employmentStatus',
            'seniority',
        ], true)) {
            return;
        }

        // Empty strings break our filters and URL persistence, so normalize them to null.
        if ('' === $this->{$propertyName}) {
            $this->{$propertyName} = null;
        }
    }

    protected function isSearching() : bool
    {
        return strlen((string) $this->query) > 1;
    }

    protected function hasActiveFilters() : bool
    {
        return filled($this->query)
            || filled($this->minSalary)
            || filled($this->maxSalary)
            || filled($this->setting)
            || filled($this->employmentStatus)
            || filled($this->seniority)
            || $this->withEquity;
    }
}
