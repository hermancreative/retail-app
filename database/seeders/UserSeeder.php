<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed default accounts used to sign in to the admin panel.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@smartretail.test'],
            [
                'name' => 'Admin SmartRetail',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );

        User::updateOrCreate(
            ['email' => 'staff@smartretail.test'],
            [
                'name' => 'Staff Toko',
                'role' => 'staff',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );
    }
}
