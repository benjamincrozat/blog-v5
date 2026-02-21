<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

/**
 * Defines the CompanySeeder implementation.
 */
class CompanySeeder extends Seeder
{
    public function run() : void
    {
        Company::factory(30)->create();
    }
}
