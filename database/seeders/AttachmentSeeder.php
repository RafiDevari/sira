<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attachment;
use App\Models\Task;
use App\Models\User;

class AttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Attachment::create([
            'task_id' => 1,
            'user_id' => 1,
            'comment' => 'Initial attachment for task 1',
        ]);
        Attachment::create([
            'task_id' => 1,
            'user_id' => 2,
            'comment' => 'Additional attachment for task 1',
        ]);
        Attachment::create([
            'task_id' => 1,
            'user_id' => 3,
            'comment' => 'Figma link : https://www.figma.com/adsaddsdsaadssaddsasdadssadasdsaddasdasdasdsaadsdsasadasdasdasdsadsadsadasdsadasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasd',
        ]);
        Attachment::create([
            'task_id' => 2,
            'user_id' => 2,
            'comment' => 'Attachment for task 2',
        ]);
        Attachment::create([
            'task_id' => 3,
            'user_id' => 1,
            'comment' => 'Attachment for task 3',
        ]);
    }
}
