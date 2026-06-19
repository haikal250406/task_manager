<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of tasks (User View)
     */
    public function index(Request $request)
    {
        $query = Task::with(['project', 'user']);

        // Filter by project
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tasks = $query->latest()->paginate(15)->withQueryString();
        $projects = Project::active()->orderBy('name')->get();
        $users = User::active()->orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'projects', 'users'));
    }

    /**
     * Show the form for creating a new task
     */
    public function create(Request $request)
    {
        $projects = Project::active()->orderBy('name')->get();
        $users = User::active()->orderBy('name')->get();
        $projectId = $request->query('project_id');

        return view('tasks.create', compact('projects', 'users', 'projectId'));
    }

    /**
     * Store a newly created task
     */
    public function store(Request $request)
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

        $task = Task::create($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'model_type' => Task::class,
            'model_id' => $task->id,
            'description' => "Membuat tugas baru: {$task->title}",
            'new_values' => $task->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('tasks.index')
            ->with('success', "Tugas '{$task->title}' berhasil dibuat!");
    }

    /**
     * Display the specified task
     */
    public function show(Task $task)
    {
        $task->load(['project', 'user']);
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task
     */
    public function edit(Task $task)
    {
        $projects = Project::active()->orderBy('name')->get();
        $users = User::active()->orderBy('name')->get();
        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    /**
     * Update the specified task
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
            'description' => "Mengupdate tugas: {$task->title}",
            'old_values' => $oldValues,
            'new_values' => $task->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('tasks.index')
            ->with('success', "Tugas '{$task->title}' berhasil diupdate!");
    }

    /**
     * Remove the specified task
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
            'description' => "Menghapus tugas: {$taskTitle}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('tasks.index')
            ->with('success', "Tugas '{$taskTitle}' berhasil dihapus!");
    }

    /**
     * Update task status (for Kanban board)
     */
    public function updateStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:to_do,in_progress,done',
        ]);

        $task->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Status tugas berhasil diupdate',
        ]);
    }
}