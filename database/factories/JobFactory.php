<?php

namespace Database\Factories;

use App\Models\Company;
use App\Enums\JobSetting;
use App\Enums\JobSeniority;
use Illuminate\Support\Arr;
use App\Enums\EmploymentStatus;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition() : array
    {

        return [
            'company_id' => Company::factory(),
            'url' => fake()->url(),
            'html' => fake()->randomHtml(),
            'source' => fake()->word(),
            'language' => fake()->languageCode(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'technologies' => fake()->words(random_int(3, 10)),
            'locations' => Collection::times(
                random_int(1, 2),
                fn () => fake()->city() . ', ' . fake()->country(),
            ),
            'setting' => Arr::random(JobSetting::values()),
            'min_salary' => $minSalary = fake()->numberBetween(10000, 100000),
            'max_salary' => fake()->numberBetween($minSalary, $minSalary * random_int(2, 4)),
            'currency' => fake()->currencyCode(),
            'equity' => fake()->boolean(),
            'employment_status' => Arr::random(EmploymentStatus::values()),
            'seniority' => Arr::random(JobSeniority::values()),
            'perks' => fake()->optional()->sentences(random_int(0, 4)) ?? [],
            'interview_process' => fake()->optional()->sentences(random_int(0, 4)) ?? [],
        ];
    }
}
