<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Division Officer (SEDD Official)
        User::updateOrCreate(
            ['email' => 'officer@sedd.lk'],
            [
                'name' => 'K. Wickramasinghe (SEDD Officer)',
                'role' => 'officer',
                'organization_name' => 'Small Enterprise Development Division',
                'password' => Hash::make('officer123'),
            ]
        );

        // Also keep/update admin@sedd.lk as officer
        User::updateOrCreate(
            ['email' => 'admin@sedd.lk'],
            [
                'name' => 'SEDD Enterprise Officer',
                'role' => 'officer',
                'organization_name' => 'Small Enterprise Development Division',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Company Management Team (Entrepreneur / Sales Team Manager)
        User::updateOrCreate(
            ['email' => 'company@agrofoods.lk'],
            [
                'name' => 'Sunimal Perera (Company Manager)',
                'role' => 'company',
                'organization_name' => 'Ceylon Pure Agro Foods',
                'password' => Hash::make('company123'),
            ]
        );
    }
}
