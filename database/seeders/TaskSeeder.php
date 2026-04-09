<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = [
            [
                'name' => 'Design System Implementation',
                'description' => 'Create a comprehensive design system for the TaskNest application including typography, colors, and components.',
                'status' => 'in_progress',
            ],
            [
                'name' => 'Authentication Setup',
                'description' => 'Implement multi-role authentication (Admin and User) using Laravel Sanctum or Fortify.',
                'status' => 'completed',
            ],
            [
                'name' => 'Task CRUD Operations',
                'description' => 'Develop full CRUD functionality for tasks including filtering and search capabilities.',
                'status' => 'pending',
            ],
            [
                'name' => 'Deployment to Staging',
                'description' => 'Setup CI/CD pipeline and deploy the current version to the staging environment.',
                'status' => 'pending',
            ],
        ];

        foreach ($tasks as $task) {
            Task::create([
                'name' => $task['name'],
                'slug' => Str::slug($task['name']),
                'description' => $task['description'],
                'status' => $task['status'],
            ]);
        }
    }
}
