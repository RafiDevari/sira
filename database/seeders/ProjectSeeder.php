<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::create([
            'key' => 'IT',
            'nama' => 'Project Apollo',
            'deskripsi' => 'First major project',
            'id_lead' => 1, // Assumes Alice
            'id_aplikasi' => 1, // Assumes App Alpha
            'waktu_mulai' => now()->subDays(30),
            'waktu_selesai' => now()->addDays(60),
            'tipe' => 'Internal',
        ]);

        Project::create([
            'key' => 'AI',
            'nama' => 'Project Gemini',
            'deskripsi' => 'Second project',
            'id_lead' => 2, // Bob
            'id_aplikasi' => 2, // App Beta
            'waktu_mulai' => now()->subDays(10),
            'waktu_selesai' => now()->addDays(90),
            'tipe' => 'Client',
        ]);
    }
}
