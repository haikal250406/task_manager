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

    /**
     * Display a listing of projects
     */
    public function index(Request $request)
    {
        $query = Project::with(['user', 'tasks']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by owner (for admin)
        if ($request->filled('user_id') && auth()->user()->isAdmin()) {
            $query->where('user_id', $request->user_id);
        }

        $projects = $query->latest()->paginate(12)->withQueryString();

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created project
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,on_hold,completed,cancelled',
        ], [
            'name.required' => 'Nama proyek wajib diisi',
            'name.max' => 'Nama proyek maksimal 255 karakter',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status tidak valid',
        ]);

        // Set user_id to current authenticated user
        $validated['user_id'] = auth()->id();

        $project = Project::create($validated);

        // Log activity
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
    }

    /**
     * Display the specified project
     */
    public function show(Project $project)
    {
        $project->load(['user', 'tasks' => function($query) {
            $query->latest();
        }]);

        $taskStats = $project->getTaskStats();

        return view('projects.show', compact('project', 'taskStats'));
    }

    /**
     * Show the form for editing the specified project
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified project
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,on_hold,completed,cancelled',
        ], [
            'name.required' => 'Nama proyek wajib diisi',
            'name.max' => 'Nama proyek maksimal 255 karakter',
            'status.required' => 'Status wajib dipilih',
        ]);

        $oldValues = $project->only(['name', 'description', 'status']);

        $project->update($validated);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'model_type' => Project::class,
            'model_id' => $project->id,
            'description' => "Mengupdate proyek: {$project->name}",
            'old_values' => $oldValues,
            'new_values' => $project->only(['name', 'description', 'status']),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('projects.index')
            ->with('success', "Proyek '{$project->name}' berhasil diupdate!");
    }

    /**
     * Remove the specified project
     */
    public function destroy(Project $project)
    {
        $projectName = $project->name;
        $oldValues = $project->toArray();

        $project->delete(); // Soft delete

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'model_type' => Project::class,
            'model_id' => $project->id,
            'description' => "Menghapus proyek: {$projectName}",
            'old_values' => $oldValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('projects.index')
            ->with('success', "Proyek '{$projectName}' berhasil dihapus!");
    }
}