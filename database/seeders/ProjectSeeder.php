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
            'key' => 'APO-001',
            'nama' => 'Project Apollo',
            'deskripsi' => 'First major project',
            'id_lead' => 1, 
            'id_aplikasi' => 1, 
            'waktu_mulai' => now()->subDays(60),
            'waktu_selesai' => now()->addDays(29),
            'tipe' => "Network",
        ]);

        Project::create([
            'key' => 'GEM-001',
            'nama' => 'Project Gemini',
            'deskripsi' => 'Second project',
            'id_lead' => 2, 
            'id_aplikasi' => 2, 
            'waktu_mulai' => now()->subDays(30),
            'waktu_selesai' => now()->addDays(59),
            'tipe' => "Software",
        ]);

        Project::create([
            'key' => 'AI-002',
            'nama' => 'Project AI',
            'deskripsi' => 'Third project',
            'id_lead' => 3, 
            'id_aplikasi' => 3, 
            'waktu_mulai' => now()->subDays(5),
            'waktu_selesai' => now()->addDays(89),
            'tipe' => "Hardware",
        ]);
    }
}
