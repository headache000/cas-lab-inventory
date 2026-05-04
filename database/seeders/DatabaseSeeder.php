<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create the 11 Laboratories
        $labs = [
            'IT Lab 1', 'IT Lab 2', 'IT Lab 3', 
            'IS Lab 1', 'IS Lab 2', 
            'Statistics Lab', 'Mathematics Lab', 'Chemistry Lab', 
            'Biology Lab', 'Psychology Lab', 'Physics Lab'
        ];

        foreach ($labs as $labName) {
            \App\Models\Laboratory::create(['name' => $labName]);
        }

        // Create the Dean User
        User::factory()->create([
            'name' => 'CAS Dean',
            'email' => 'dean@example.com',
            'password' => bcrypt('password123'),
            'role' => 'dean',
        ]);

        // Create a Lab Admin User
        User::factory()->create([
            'name' => 'IT Lab 1 Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'laboratory_id' => 1, // IT Lab 1
        ]);
    }
}
