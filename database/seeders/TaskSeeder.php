<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Sprint;
use App\Models\User;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        Task::create([
            'name' => 'Design wireframes',
            'sprint_id' => 1,
            'user_id' => 1,
            'status' => 'IN REVIEW',
            'type' => 'Bug',
            'description' => 'Create initial wireframes for the project',
            'waktu_mulai' => now()->subDays(2),
            'waktu_selesai' => now()->subDays(7),
        ]);

        Task::create([
            'name' => 'Setup backend API',
            'sprint_id' => 1,
            'user_id' => null,
            'status' => 'IN PROGRESS',
            'type' => 'Feature',
            'description' => 'Set up the initial backend API structure',
            'waktu_mulai' => now()->subDays(1),
            'waktu_selesai' => now()->subDays(3),

        ]);

        Task::create([
            'name' => 'Write unit tests',
            'sprint_id' => 2,
            'user_id' => 2,
            'status' => 'DONE',
            'type' => 'Task',
            'description' => 'Write unit tests for the existing codebase',
            'waktu_mulai' => now()->subDays(5),
            'waktu_selesai' => now()->subDays(1),

        ]);
        Task::create([
            'name' => 'Update documentation',
            'sprint_id' => 2,
            'user_id' => 4,
            'status' => 'TO DO',
            'type' => 'Request',
            'description' => 'Update the project documentation with the latest changes',
            'waktu_mulai' => now()->subDays(3),
            'waktu_selesai' => now()->addDays(2),
        ]);

        Task::create([
            'name' => 'Refactor codebase',
            'sprint_id' => 1,
            'user_id' => 4,
            'status' => 'IN PROGRESS',
            'type' => 'Story',
            'description' => 'Refactor the codebase to improve maintainability',
            'waktu_mulai' => now()->subDays(4),
            'waktu_selesai' => now()->addDays(1),
        ]);

        Task::create([
            'name' => 'Design wireframes',
            'sprint_id' => 3,
            'user_id' => 1,
            'status' => 'IN PROGRESS',
            'type' => 'Bug',
            'description' => 'Create wireframes for the new feature',
            'waktu_mulai' => now()->subDays(10),
            'waktu_selesai' => now()->subDays(5),
        ]);

        Task::create([
            'name' => 'Setup backend API',
            'sprint_id' => 3,
            'user_id' => null,
            'status' => 'pending',
            'type' => 'Feature',
            'description' => 'Set up the backend API for the new feature',
            'waktu_mulai' => now()->subDays(8),
            'waktu_selesai' => now()->subDays(3),
        ]);

        Task::create([
            'name' => 'Write unit tests',
            'sprint_id' => 4,
            'user_id' => 2,
            'status' => 'completed',
            'type' => 'Task',
            'description' => 'Write unit tests for the new feature',
            'waktu_mulai' => now()->subDays(6),
            'waktu_selesai' => now()->subDays(2),
        ]);

        Task::create([
            'name' => 'Design wireframes',
            'sprint_id' => 5,
            'user_id' => 1,
            'status' => 'in progress',
            'type' => 'Bug',
            'description' => 'Create wireframes for the next sprint',
            'waktu_mulai' => now()->subDays(12),
            'waktu_selesai' => now()->subDays(7),
        ]);

        Task::create([
            'name' => 'Setup backend API',
            'sprint_id' => 5,
            'user_id' => null,
            'status' => 'pending',
            'type' => 'Feature',
            'description' => 'Set up the backend API for the next sprint',
            'waktu_mulai' => now()->subDays(10),
            'waktu_selesai' => now()->subDays(5),
        ]);

        Task::create([
            'name' => 'Write unit tests',
            'sprint_id' => 6,
            'user_id' => 2,
            'status' => 'completed',
            'type' => 'Task',
            'description' => 'Write unit tests for the next sprint',
            'waktu_mulai' => now()->subDays(8),
            'waktu_selesai' => now()->subDays(3),
        ]);

        Task::create([
            'name' => 'Design wireframes',
            'sprint_id' => 7,
            'user_id' => 1,
            'status' => 'in progress',
            'type' => 'Bug',
            'description' => 'Create wireframes for the upcoming sprint',
            'waktu_mulai' => now()->subDays(14),
            'waktu_selesai' => now()->subDays(9),
        ]);

        Task::create([
            'name' => 'Setup backend API',
            'sprint_id' => 7,
            'user_id' => null,
            'status' => 'pending',
            'type' => 'Feature',
            'description' => 'Set up the backend API for the upcoming sprint',
            'waktu_mulai' => now()->subDays(12),
            'waktu_selesai' => now()->subDays(7),
        ]);

        Task::create([
            'name' => 'Write unit tests',
            'sprint_id' => 8,
            'user_id' => 2,
            'status' => 'completed',
            'type' => 'Task',
            'description' => 'Write unit tests for the upcoming sprint',
            'waktu_mulai' => now()->subDays(10),
            'waktu_selesai' => now()->subDays(5),
        ]);

        Task::create([
            'name' => 'Design wireframes',
            'sprint_id' => 9,
            'user_id' => 1,
            'status' => 'in progress',
            'type' => 'Feature',
            'description' => 'Create wireframes for the next project phase',
            'waktu_mulai' => now()->subDays(20),
            'waktu_selesai' => now()->subDays(15),
        ]);

        Task::create([
            'name' => 'Setup backend API',
            'sprint_id' => 9,
            'user_id' => null,
            'status' => 'pending',
            'type' => 'Feature',
            'description' => 'Set up the backend API for the next project phase',
            'waktu_mulai' => now()->subDays(18),
            'waktu_selesai' => now()->subDays(13),
        ]);

        Task::create([
            'name' => 'Write unit tests',
            'sprint_id' => 10,
            'user_id' => 2,
            'status' => 'completed',
            'type' => 'Task',
            'description' => 'Write unit tests for the next project phase',
            'waktu_mulai' => now()->subDays(16),
            'waktu_selesai' => now()->subDays(11),
        ]);

        Task::create([
            'name' => 'Design wireframes',
            'sprint_id' => 11,
            'user_id' => 1,
            'status' => 'in progress',
            'type' => 'Bug',
            'description' => 'Create wireframes for the next sprint',
            'waktu_mulai' => now()->subDays(24),
            'waktu_selesai' => now()->subDays(19),
        ]);

        Task::create([
            'name' => 'Setup backend API',
            'sprint_id' => 11,
            'user_id' => null,
            'status' => 'pending',
            'type' => 'Feature',
            'description' => 'Set up the backend API for the next sprint',
            'waktu_mulai' => now()->subDays(22),
            'waktu_selesai' => now()->subDays(17),
        ]);

        Task::create([
            'name' => 'Write unit tests',
            'sprint_id' => 12,
            'user_id' => 2,
            'status' => 'completed',
            'type' => 'Task',
            'description' => 'Write unit tests for the next sprint',
            'waktu_mulai' => now()->subDays(20),
            'waktu_selesai' => now()->subDays(15),
        ]);
    }
}

