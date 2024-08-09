<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use Illuminate\Support\Str;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 10 dummy tasks
        for ($i = 1; $i <= 10; $i++) {
            Task::create([
                'title' => 'Task ' . $i,
                'description' => 'Description for Task ' . $i,
                'is_completed' => $i % 2 == 0, // Every second task is marked as completed
            ]);
        }
    }
}
