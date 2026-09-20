<?php

namespace Database\Seeders;

use App\Domain\Settings\Models\CompanyProfile;
use Illuminate\Database\Seeder;

class CompanyProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (CompanyProfile::query()->exists()) {
            return;
        }

        CompanyProfile::create([
            'name' => config('app.name', 'SmartRetail'),
            'email' => 'hello@smartretail.test',
        ]);
    }
}
