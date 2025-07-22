<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Application;


class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Application::create(['name' => 'App Alpha']);
        Application::create(['name' => 'App Beta']);
        Application::create(['name' => 'App Gamma']);
        

    }
}
