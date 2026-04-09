<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query()
            ->search($request->string('search')->toString())
            ->filterStatus($request->string('status')->toString())
            ->filterPriority($request->string('priority')->toString())
            ->filterDue($request->string('due')->toString());

        $perPage = (int) $request->input('per_page', 12);
        $perPage = in_array($perPage, [12, 24, 48], true) ? $perPage : 12;

        $tasks = (clone $query)
            ->applySort($request->string('sort')->toString())
            ->paginate($perPage)
            ->withQueryString();

        $taskStats = [
            'total' => Task::count(),
            'completed' => Task::where('status', Task::STATUS_COMPLETED)->count(),
            'in_progress' => Task::where('status', Task::STATUS_IN_PROGRESS)->count(),
            'overdue' => Task::whereDate('due_date', '<', now()->toDateString())
                ->where('status', '!=', Task::STATUS_COMPLETED)
                ->count(),
        ];

        return view('pages.tasks.index', [
            'title' => 'Tasks',
            'tasks' => $tasks,
            'taskStats' => $taskStats,
            'statusOptions' => Task::statusOptions(),
            'priorityOptions' => Task::priorityOptions(),
        ]);
    }

    public function modify(?Task $task = null)
    {
        if (! $task || ! $task->exists) {
            $task = new Task;
        }

        return view('pages.tasks.modify', [
            'title' => $task->exists ? 'Edit Task' : 'Create Task',
            'task' => $task,
            'statusOptions' => Task::statusOptions(),
            'priorityOptions' => Task::priorityOptions(),
        ]);
    }

    public function save(Request $request, ?Task $task = null)
    {
        $isUpdate = $task && $task->exists;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:'.implode(',', array_keys(Task::statusOptions())),
            'priority' => 'required|in:'.implode(',', array_keys(Task::priorityOptions())),
            'due_date' => 'nullable|date',
        ]);

        $validated['completed_at'] = $validated['status'] === Task::STATUS_COMPLETED ? now() : null;

        DB::transaction(function () use ($validated, &$task, $isUpdate) {
            $task = $task ?? new Task;

            if ($isUpdate) {
                $task->update($validated);
            } else {
                $task = Task::create($validated);
            }
        });

        return redirect()->route('dashboard.tasks.index')->with('success', $isUpdate ? 'Task updated successfully.' : 'Task created successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('dashboard.tasks.index')->with('success', 'Task deleted successfully.');
    }
}
