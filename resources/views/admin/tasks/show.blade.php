@extends('layouts.admin')

@section('title', 'Detail Tugas')
@section('page-title', 'Detail Tugas')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Detail Tugas</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body p-4">
        <h3 class="fw-bold text-dark mb-3">{{ $task->title }}</h3>

        <div class="row mb-4">
            <div class="col-md-6">
                <p class="mb-2"><strong>Proyek:</strong> 
                    <span class="badge bg-primary">{{ $task->project->name ?? '-' }}</span>
                </p>
                <p class="mb-2"><strong>Status:</strong> 
                    <span class="badge {{ $task->getStatusBadgeClass() }}">{{ $task->getStatusText() }}</span>
                </p>
            </div>
            <div class="col-md-6">
                <p class="mb-2"><strong>Prioritas:</strong> 
                    <span class="badge {{ $task->getPriorityBadgeClass() }}">{{ $task->getPriorityText() }}</span>
                </p>
                <p class="mb-2"><strong>Deadline:</strong> 
                    @if($task->deadline)
                        <span class="{{ $task->isOverdue() ? 'text-danger fw-bold' : '' }}">
                            {{ $task->deadline->format('d M Y') }}
                            @if($task->isOverdue())
                                <i class="fas fa-exclamation-triangle ms-1"></i>
                            @endif
                        </span>
                    @else
                        <span class="text-muted">Tidak ada deadline</span>
                    @endif
                </p>
            </div>
        </div>

        @if($task->description)
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-2">Deskripsi</h6>
                <p class="text-muted">{{ $task->description }}</p>
            </div>
        @endif

        <div class="mb-4">
            <h6 class="fw-bold text-dark mb-2">Assigned To</h6>
            @if($task->user)
                <div class="d-flex align-items-center">
                    <img src="{{ $task->user->getAvatarUrl() }}" alt="{{ $task->user->name }}" class="rounded-circle me-2" width="40" height="40">
                    <div>
                        <div class="fw-bold">{{ $task->user->name }}</div>
                        <small class="text-muted">{{ $task->user->email }}</small>
                    </div>
                </div>
            @else
                <p class="text-muted">Unassigned</p>
            @endif
        </div>

        <hr>

        <div class="row text-muted small">
            <div class="col-md-6">
                <p class="mb-1"><i class="far fa-clock me-1"></i>Dibuat: {{ $task->created_at->format('d M Y H:i') }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-1"><i class="fas fa-sync me-1"></i>Terakhir diupdate: {{ $task->updated_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection