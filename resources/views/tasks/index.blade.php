@extends('layouts.app')

@section('title', 'Daftar Tugas')

@section('content')
<div class="container-fluid py-4 px-3">
    <!-- Header -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3 mb-4">
        <div>
            <h1 class="display-5 fw-bold text-dark">Daftar Tugas</h1>
            <p class="text-muted mb-0">Kelola dan lacak semua tugas proyek Anda</p>
        </div>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary rounded-3 px-4 py-2 fw-medium shadow-sm">
            <i class="fas fa-plus me-2"></i> Tambah Tugas
        </a>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('tasks.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari judul atau deskripsi..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="project_id" class="form-select">
                            <option value="">Semua Proyek</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="to_do" {{ request('status') == 'to_do' ? 'selected' : '' }}>To Do</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="priority" class="form-select">
                            <option value="">Semua Prioritas</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tasks Table -->
    @if($tasks->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Judul Tugas</th>
                                <th>Proyek</th>
                                <th>Status</th>
                                <th>Prioritas</th>
                                <th>Deadline</th>
                                <th>Assigned To</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                                <tr class="{{ $task->isOverdue() ? 'table-warning' : '' }}">
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $task->title }}</div>
                                        @if($task->description)
                                            <small class="text-muted">{{ Str::limit($task->description, 60) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            {{ $task->project->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $task->getStatusBadgeClass() }}">
                                            {{ $task->getStatusText() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $task->getPriorityBadgeClass() }}">
                                            {{ $task->getPriorityText() }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($task->deadline)
                                            <div class="{{ $task->isOverdue() ? 'text-danger fw-bold' : 'text-muted' }}">
                                                <i class="far fa-calendar me-1"></i>
                                                {{ $task->deadline->format('d M Y') }}
                                            </div>
                                            @if($task->isOverdue())
                                                <small class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>Terlambat</small>
                                            @endif
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($task->user)
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $task->user->getAvatarUrl() }}" 
                                                     alt="{{ $task->user->name }}" 
                                                     class="rounded-circle me-2" width="30" height="30">
                                                <small>{{ $task->user->name }}</small>
                                            </div>
                                        @else
                                            <small class="text-muted">Unassigned</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"
                                                        onclick="return confirm('Yakin ingin menghapus tugas {{ $task->title }}?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($tasks->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-center">
                        {{ $tasks->links() }}
                    </div>
                </div>
            @endif
        </div>
    @else
        <!-- Empty State -->
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-clipboard-list fa-4x text-muted opacity-25 mb-3"></i>
                <h4 class="text-muted">Belum ada tugas</h4>
                <p class="text-muted mb-4">Mulai buat tugas pertama Anda untuk proyek ini</p>
                <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Tambah Tugas Pertama
                </a>
            </div>
        </div>
    @endif
</div>
@endsection