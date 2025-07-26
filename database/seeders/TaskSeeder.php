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
            'status' => 'in progress',
        ]);

        Task::create([
            'name' => 'Setup backend API',
            'sprint_id' => 1,
            'user_id' => null,
            'status' => 'pending',
        ]);

        Task::create([
            'name' => 'Write unit tests',
            'sprint_id' => 2,
            'user_id' => 2,
            'status' => 'completed',
        ]);

        Task::create([
            'name' => 'Design wireframes',
            'sprint_id' => 3,
            'user_id' => 1,
            'status' => 'in progress',
        ]);

        Task::create([
            'name' => 'Setup backend API',
            'sprint_id' => 3,
            'user_id' => null,
            'status' => 'pending',
        ]);

        Task::create([
            'name' => 'Write unit tests',
            'sprint_id' => 4,
            'user_id' => 2,
            'status' => 'completed',
        ]);

        Task::create([
            'name' => 'Design wireframes',
            'sprint_id' => 5,
            'user_id' => 1,
            'status' => 'in progress',
        ]);

        Task::create([
            'name' => 'Setup backend API',
            'sprint_id' => 5,
            'user_id' => null,
            'status' => 'pending',
        ]);

        Task::create([
            'name' => 'Write unit tests',
            'sprint_id' => 6,
            'user_id' => 2,
            'status' => 'completed',
        ]);

        Task::create([
            'name' => 'Design wireframes',
            'sprint_id' => 7,
            'user_id' => 1,
            'status' => 'in progress',
        ]);

        Task::create([
            'name' => 'Setup backend API',
            'sprint_id' => 7,
            'user_id' => null,
            'status' => 'pending',
        ]);

        Task::create([
            'name' => 'Write unit tests',
            'sprint_id' => 8,
            'user_id' => 2,
            'status' => 'completed',
        ]);

        Task::create([
            'name' => 'Design wireframes',
            'sprint_id' => 9,
            'user_id' => 1,
            'status' => 'in progress',
        ]);

        Task::create([
            'name' => 'Setup backend API',
            'sprint_id' => 9,
            'user_id' => null,
            'status' => 'pending',
        ]);

        Task::create([
            'name' => 'Write unit tests',
            'sprint_id' => 10,
            'user_id' => 2,
            'status' => 'completed',
        ]);

        Task::create([
            'name' => 'Design wireframes',
            'sprint_id' => 11,
            'user_id' => 1,
            'status' => 'in progress',
        ]);

        Task::create([
            'name' => 'Setup backend API',
            'sprint_id' => 11,
            'user_id' => null,
            'status' => 'pending',
        ]);

        Task::create([
            'name' => 'Write unit tests',
            'sprint_id' => 12,
            'user_id' => 2,
            'status' => 'completed',
        ]);
    }
}

