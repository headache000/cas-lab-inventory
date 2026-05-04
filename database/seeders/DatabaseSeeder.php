<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Laboratory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $itLab1 = Laboratory::updateOrCreate(
            ['name' => 'IT Lab 1'],
            ['description' => 'Information Technology Laboratory 1']
        );

        Laboratory::updateOrCreate(
            ['name' => 'IT Lab 2'],
            ['description' => 'Information Technology Laboratory 2']
        );

        Laboratory::updateOrCreate(
            ['name' => 'IT Lab 3'],
            ['description' => 'Information Technology Laboratory 3']
        );

        Laboratory::updateOrCreate(
            ['name' => 'IS Lab 1'],
            ['description' => 'Information Systems Laboratory 1']
        );

        Laboratory::updateOrCreate(
            ['name' => 'IS Lab 2'],
            ['description' => 'Information Systems Laboratory 2']
        );

        User::updateOrCreate(
            ['email' => 'dean@example.com'],
            [
                'name' => 'CAS Dean',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'dean',
                'laboratory_id' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Lab Administrator',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'laboratory_id' => $itLab1->id,
            ]
        );
    }
}