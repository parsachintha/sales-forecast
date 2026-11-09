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
        User::updateOrCreate(
            ['email' => 'admin@sedd.lk'],
            [
                'name' => 'SEDD Enterprise Officer',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
