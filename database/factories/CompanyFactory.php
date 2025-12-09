<?php

namespace Database\Factories;

use App\Actions\NormalizeCompanyUrl;
use App\Actions\NormalizeCompanyDomain;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'name' => fake()->unique()->company(),
            'url' => fake()->url(),
            'logo' => fake()->imageUrl(),
            'about' => fake()->paragraphs(random_int(1, 3), true),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function ($company) {
            /** @var NormalizeCompanyUrl $urlNormalizer */
            $urlNormalizer = app(NormalizeCompanyUrl::class);
            /** @var NormalizeCompanyDomain $domainNormalizer */
            $domainNormalizer = app(NormalizeCompanyDomain::class);

            $normalizedUrl = $urlNormalizer->handle($company->url);
            $normalizedDomain = $domainNormalizer->handle($normalizedUrl ?? $company->url);

            $company->url = $normalizedUrl;
            $company->domain = $normalizedDomain;
        })->afterCreating(function ($company) {
            /** @var NormalizeCompanyUrl $urlNormalizer */
            $urlNormalizer = app(NormalizeCompanyUrl::class);
            /** @var NormalizeCompanyDomain $domainNormalizer */
            $domainNormalizer = app(NormalizeCompanyDomain::class);

            $normalizedUrl = $urlNormalizer->handle($company->url);
            $normalizedDomain = $domainNormalizer->handle($normalizedUrl ?? $company->url);

            $company->updateQuietly([
                'url' => $normalizedUrl,
                'domain' => $normalizedDomain,
            ]);
        });
    }
}
