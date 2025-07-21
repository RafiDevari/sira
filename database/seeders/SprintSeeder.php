<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sprint;


class SprintSeeder extends Seeder
{
    public function run(): void
    {
        Sprint::create([
            'id_project' => 1,
            'nama' => 'Sprint 1',
            'waktu_mulai' => now()->subDays(30),
            'waktu_selesai' => now()->subDays(15),
            'status' => 'completed',
        ]);

        Sprint::create([
            'id_project' => 1,
            'nama' => 'Sprint 2',
            'waktu_mulai' => now()->subDays(14),
            'waktu_selesai' => now()->addDays(1),
            'status' => 'in progress',
        ]);
    }
}
