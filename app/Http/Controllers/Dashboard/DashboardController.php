<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $taskStats = [
            'total' => Task::count(),
            'pending' => Task::where('status', Task::STATUS_PENDING)->count(),
            'in_progress' => Task::where('status', Task::STATUS_IN_PROGRESS)->count(),
            'completed' => Task::where('status', Task::STATUS_COMPLETED)->count(),
            'overdue' => Task::whereDate('due_date', '<', now()->toDateString())
                ->where('status', '!=', Task::STATUS_COMPLETED)
                ->count(),
        ];

        return view('pages.dashboard.index', [
            'title' => 'Dashboard',
            'taskStats' => $taskStats,
            'recentTasks' => Task::latest()->take(5)->get(),
            'upcomingTasks' => Task::whereNotNull('due_date')
                ->where('status', '!=', Task::STATUS_COMPLETED)
                ->whereDate('due_date', '>=', now()->toDateString())
                ->orderBy('due_date')
                ->take(4)
                ->get(),
        ]);
    }
}
