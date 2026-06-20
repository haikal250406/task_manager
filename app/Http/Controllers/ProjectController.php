<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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

        // Check if request wants JSON (AJAX)
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Proyek berhasil dibuat',
                'project' => $project,
            ], 201);
        }

        return redirect()->route('projects.index')
            ->with('success', "Proyek '{$project->name}' berhasil dibuat!");
    }

    public function show(Project $project)
    {
        $project->load(['user', 'tasks' => function($query) {
            $query->latest();
        }]);

        $taskStats = $project->getTaskStats();

        return view('projects.show', compact('project', 'taskStats'));
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