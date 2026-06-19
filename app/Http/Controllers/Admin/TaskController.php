<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of all tasks (Admin View)
     */
    public function index(Request $request)
    {
        $query = Task::with(['project', 'user']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by project
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Filter by assigned user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->latest()->paginate(15)->withQueryString();
        $projects = Project::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('admin.tasks.index', compact('tasks', 'projects', 'users'));
    }

    /**
     * Display the specified task (Admin View)
     */
    public function show(Task $task)
    {
        $task->load(['project', 'user']);
        return view('admin.tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task (Admin View)
     */
    public function edit(Task $task)
    {
        $projects = Project::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        return view('admin.tasks.edit', compact('task', 'projects', 'users'));
    }

    /**
     * Update the specified task (Admin)
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:to_do,in_progress,done',
            'priority' => 'required|in:low,medium,high,critical',
            'deadline' => 'nullable|date',
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $oldValues = $task->toArray();
        $task->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'model_type' => Task::class,
            'model_id' => $task->id,
            'description' => "Admin mengupdate tugas: {$task->title}",
            'old_values' => $oldValues,
            'new_values' => $task->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.tasks.index')
            ->with('success', "Tugas '{$task->title}' berhasil diupdate!");
    }

    /**
     * Remove the specified task (Admin)
     */
    public function destroy(Task $task)
    {
        $taskTitle = $task->title;
        $task->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'model_type' => Task::class,
            'model_id' => $task->id,
            'description' => "Admin menghapus tugas: {$taskTitle}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.tasks.index')
            ->with('success', "Tugas '{$taskTitle}' berhasil dihapus!");
    }
}