@extends('layouts.admin')

@section('title', 'Manajemen Tugas')
@section('page-title', 'Manajemen Tugas')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Daftar Semua Tugas</h5>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Tugas
        </a>
    </div>
    <div class="card-body">
        <!-- Filter & Search -->
        <form method="GET" action="{{ route('admin.tasks.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari tugas..." value="{{ request('search') }}">
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
                <div class="col-md-3 d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Judul Tugas</th>
                        <th>Proyek</th>
                        <th>Status</th>
                        <th>Prioritas</th>
                        <th>Deadline</th>
                        <th>Assigned To</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                        <tr class="{{ $task->isOverdue() ? 'table-warning' : '' }}">
                            <td>
                                <div class="fw-bold text-dark">{{ $task->title }}</div>
                                @if($task->description)
                                    <small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
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
                                        <img src="{{ $task->user->getAvatarUrl() }}" alt="{{ $task->user->name }}" class="rounded-circle me-2" width="30" height="30">
                                        <small>{{ $task->user->name }}</small>
                                    </div>
                                @else
                                    <small class="text-muted">Unassigned</small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.tasks.show', $task) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus tugas {{ $task->title }}?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-tasks fa-3x mb-3 opacity-25"></i>
                                <p>Tidak ada tugas ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($tasks->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>
</div>
@endsection