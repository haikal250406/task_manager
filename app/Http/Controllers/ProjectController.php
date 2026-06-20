<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $projects = Project::with('user')->latest()->paginate(10);
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'status' => 'required|in:active,on_hold,completed,cancelled',
            ], [
                'name.required' => 'Nama proyek wajib diisi',
                'status.required' => 'Status wajib dipilih',
            ]);

            $project = Project::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'],
                'user_id' => auth()->id(),
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'create',
                'model_type' => Project::class,
                'model_id' => $project->id,
                'description' => "Membuat proyek baru: {$project->name}",
                'new_values' => $project->toArray(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('projects.index')
                ->with('success', "Proyek '{$project->name}' berhasil dibuat!");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat proyek: ' . $e->getMessage());
        }
    }

    public function show(Project $project)
    {
        $project->load(['user', 'tasks' => function($query) {
            $query->with(['user'])->latest();
        }]);

        $tasksBaru = $project->tasks->filter(function($task) {
            return in_array(strtolower($task->status), ['to_do', 'backlog', 'baru']);
        });

        $tasksSedangDikerjakan = $project->tasks->filter(function($task) {
            return in_array(strtolower($task->status), ['in_progress', 'review', 'sedang_dikerjakan']);
        });

        $tasksSelesai = $project->tasks->filter(function($task) {
            return in_array(strtolower($task->status), ['done', 'completed', 'selesai']);
        });

        $totalTasks = $project->tasks->count();
        $completedCount = $tasksSelesai->count();
        $progressPercentage = $totalTasks > 0 ? round(($completedCount / $totalTasks) * 100) : 0;

        $notifications = [];
        
        $upcomingDeadlines = $project->tasks->filter(function($task) {
            return $task->deadline && 
                   $task->deadline->diffInDays(now()) <= 3 && 
                   !in_array(strtolower($task->status), ['done', 'completed', 'selesai']);
        });
        
        if ($upcomingDeadlines->count() > 0) {
            $notifications[] = [
                'type' => 'warning',
                'icon' => 'fa-exclamation-triangle',
                'message' => $upcomingDeadlines->count() . ' tugas mendekati deadline!',
                'tasks' => $upcomingDeadlines
            ];
        }
        
        $overdueTasks = $project->tasks->filter(function($task) {
            return $task->deadline && 
                   $task->deadline->isPast() && 
                   !in_array(strtolower($task->status), ['done', 'completed', 'selesai']);
        });
        
        if ($overdueTasks->count() > 0) {
            $notifications[] = [
                'type' => 'danger',
                'icon' => 'fa-times-circle',
                'message' => $overdueTasks->count() . ' tugas sudah melewati deadline!',
                'tasks' => $overdueTasks
            ];
        }

        $newTasks = $project->tasks->filter(function($task) {
            return $task->created_at->diffInDays(now()) <= 1;
        });
        
        if ($newTasks->count() > 0) {
            $notifications[] = [
                'type' => 'info',
                'icon' => 'fa-info-circle',
                'message' => $newTasks->count() . ' tugas baru ditambahkan',
                'tasks' => $newTasks
            ];
        }

        $activeTasks = $tasksSedangDikerjakan->filter(function($task) {
            return $task->user_id !== null;
        });
        
        if ($activeTasks->count() > 0) {
            $notifications[] = [
                'type' => 'primary',
                'icon' => 'fa-spinner',
                'message' => $activeTasks->count() . ' tugas sedang dikerjakan tim',
                'tasks' => $activeTasks
            ];
        }

        return view('projects.show', compact(
            'project',
            'tasksBaru',
            'tasksSedangDikerjakan',
            'tasksSelesai',
            'totalTasks',
            'completedCount',
            'progressPercentage',
            'notifications'
        ));
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,on_hold,completed,cancelled',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', "Proyek '{$project->name}' berhasil diupdate!");
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', "Proyek '{$project->name}' berhasil dihapus!");
    }
}