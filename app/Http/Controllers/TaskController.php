<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->input('per_page', 15);
        $tasks = $query->latest()->paginate($perPage)->withQueryString();

        return view('pages.tasks.index', [
            'title' => 'Tasks',
            'tasks' => $tasks,
        ]);
    }

    public function modify(?Task $task = null)
    {
        if (!$task || !$task->exists) {
            $task = new Task();
        }

        return view('pages.tasks.modify', [
            'title' => $task->exists ? 'Edit Task' : 'Create Task',
            'task' => $task,
        ]);
    }

    public function save(Request $request, ?Task $task = null)
    {
        $isUpdate = $task && $task->exists;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        DB::transaction(function () use ($request, $validated, &$task, $isUpdate) {
            $task = $task ?? new Task();

            if ($isUpdate) {
                $task->update($validated);
            } else {
                $task = Task::create($validated);
            }
        });

        return redirect()->route('dashboard.tasks.index')->with('success', 'Task saved successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('dashboard.tasks.index')->with('success', 'Task deleted successfully.');
    }
}
