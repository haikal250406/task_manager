@extends('layouts.app')

@section('title', $project->name)

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-2">{{ $project->name }}</h2>
                    <div class="d-flex gap-3 text-muted small flex-wrap">
                        <span><i class="fas fa-user me-1"></i>{{ $project->user->name ?? 'Unknown' }}</span>
                        <span><i class="far fa-clock me-1"></i>{{ $project->created_at->format('d M Y') }}</span>
                        <span class="badge bg-primary">{{ ucfirst($project->status ?? 'active') }}</span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Tambah Tugas
                    </a>
                </div>
            </div>

            @if($project->description)
                <hr>
                <p class="text-muted mb-0">{{ $project->description }}</p>
            @endif
        </div>
    </div>

    @php
        $allTasks = $project->tasks ?? collect();
        $totalTasks = $allTasks->count();
        $completedTasks = $allTasks->filter(fn($t) => in_array(strtolower($t->status), ['done', 'completed', 'selesai']))->count();
        $inProgressTasks = $allTasks->filter(fn($t) => strtolower($t->status) === 'in_progress')->count();
        $todoTasks = $allTasks->filter(fn($t) => strtolower($t->status) === 'to_do')->count();
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-primary mb-0">{{ $totalTasks }}</h3>
                    <small class="text-muted">Total Tugas</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-success mb-0">{{ $completedTasks }}</h3>
                    <small class="text-muted">Selesai</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-warning mb-0">{{ $inProgressTasks }}</h3>
                    <small class="text-muted">In Progress</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-secondary bg-opacity-10">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-secondary mb-0">{{ $todoTasks }}</h3>
                    <small class="text-muted">To Do</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Daftar Tugas</h5>
        </div>
        <div class="card-body">
            @if($allTasks->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Status</th>
                                <th>Prioritas</th>
                                <th>Deadline</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allTasks as $task)
                                <tr>
                                    <td><strong>{{ $task->title }}</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $task->status === 'done' ? 'success' : ($task->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ in_array(strtolower($task->priority), ['high', 'critical']) ? 'danger' : (strtolower($task->priority) === 'medium' ? 'warning' : 'info') }}">
                                            {{ $task->priority }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($task->deadline)
                                            <small>{{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-3x text-muted opacity-25 mb-3"></i>
                    <p class="text-muted">Belum ada tugas di proyek ini</p>
                    <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Tugas Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection