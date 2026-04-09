<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

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
                'priority' => 'high',
                'due_date' => now()->addDays(2)->toDateString(),
            ],
            [
                'name' => 'Authentication Setup',
                'description' => 'Implement multi-role authentication (Admin and User) using Laravel Sanctum or Fortify.',
                'status' => 'completed',
                'priority' => 'medium',
                'due_date' => now()->subDays(1)->toDateString(),
                'completed_at' => now()->subHours(12),
            ],
            [
                'name' => 'Task CRUD Operations',
                'description' => 'Develop full CRUD functionality for tasks including filtering and search capabilities.',
                'status' => 'pending',
                'priority' => 'high',
                'due_date' => now()->addDay()->toDateString(),
            ],
            [
                'name' => 'Deployment to Staging',
                'description' => 'Setup CI/CD pipeline and deploy the current version to the staging environment.',
                'status' => 'pending',
                'priority' => 'low',
                'due_date' => now()->addDays(5)->toDateString(),
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
