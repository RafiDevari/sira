<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(['name' => 'Alice']);
        User::create(['name' => 'Bob']);
        User::create(['name' => 'Charlie']);
        User::create(['name' => 'Diana']);
        User::create(['name' => 'Ethan']);
    }
}
