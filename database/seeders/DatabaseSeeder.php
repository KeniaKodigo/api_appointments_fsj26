<?php

namespace Database\Seeders;

//namespace

use App\Models\Patient;
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
        //User::factory(15)->create(); //insert....
        //crear pacientes
        Patient::factory(10)->create();
        //patients
        //citas
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
