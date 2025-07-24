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
            'key' => 'APO',
            'nama' => 'Project Apollo',
            'deskripsi' => 'First major project',
            'id_lead' => 1, 
            'id_aplikasi' => 1, 
            'waktu_mulai' => now()->subDays(90),
            'waktu_selesai' => now()->addDays(0),
            'tipe' => "Network",
        ]);

        Project::create([
            'key' => 'GEM',
            'nama' => 'Project Gemini',
            'deskripsi' => 'Second project',
            'id_lead' => 2, 
            'id_aplikasi' => 2, 
            'waktu_mulai' => now()->subDays(60),
            'waktu_selesai' => now()->addDays(30),
            'tipe' => "Software",
        ]);

        Project::create([
            'key' => 'AI-1',
            'nama' => 'Project AI',
            'deskripsi' => 'Third project',
            'id_lead' => 3, 
            'id_aplikasi' => 3, 
            'waktu_mulai' => now()->subDays(5),
            'waktu_selesai' => now()->addDays(89),
            'tipe' => "Hardware",
        ]);

        Project::create([
            'key' => 'DEV',
            'nama' => 'Project Development',
            'deskripsi' => 'Fourth project',
            'id_lead' => 4, 
            'id_aplikasi' => 2, 
            'waktu_mulai' => now()->subDays(30),
            'waktu_selesai' => now()->addDays(60),
            'tipe' => "Software",
        ]);

        Project::create([
            'key' => 'TEST',
            'nama' => 'Project Testing',
            'deskripsi' => 'Fifth project',
            'id_lead' => 5, 
            'id_aplikasi' => 1, 
            'waktu_mulai' => now()->subDays(15),
            'waktu_selesai' => now()->addDays(45),
            'tipe' => "Network",
        ]);

        Project::create([
            'key' => 'DEPLOY',
            'nama' => 'Project Deployment',
            'deskripsi' => 'Sixth project',
            'id_lead' => 3, 
            'id_aplikasi' => 2, 
            'waktu_mulai' => now()->subDays(10),
            'waktu_selesai' => now()->addDays(20),
            'tipe' => "Software",
        ]);

    }
}
