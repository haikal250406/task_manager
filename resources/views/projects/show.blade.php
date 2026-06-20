@extends('layouts.app')

@section('title', $project->name)

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-8">
                    <div class="d-flex align-items-start gap-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-folder-open text-primary fa-2x"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h2 class="fw-bold mb-2">{{ $project->name }}</h2>
                            <p class="text-muted mb-2">{{ $project->description ?? 'Tidak ada deskripsi' }}</p>
                            <div class="d-flex gap-3 flex-wrap">
                                <span class="badge bg-primary">{{ ucfirst($project->status) }}</span>
                                <span class="text-muted small">
                                    <i class="far fa-calendar me-1"></i>Dibuat {{ $project->created_at->format('d M Y') }}
                                </span>
                                <span class="text-muted small">
                                    <i class="far fa-user me-1"></i>{{ $project->user->name ?? 'Unknown' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="btn btn-primary btn-lg mb-2">
                        <i class="fas fa-plus me-2"></i>Tambah Tugas Baru
                    </a>
                    <br>
                    <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="mt-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-semibold">Progres Proyek</span>
                    <span class="fw-bold text-primary">{{ $progressPercentage ?? 0 }}%</span>
                </div>
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                         style="width: {{ $progressPercentage ?? 0 }}%" 
                         aria-valuenow="{{ $progressPercentage ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
                        {{ $completedCount ?? 0 }}/{{ $totalTasks ?? 0 }} Tugas Selesai
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($notifications) && count($notifications) > 0)
        <div class="mb-4">
            @foreach($notifications as $notif)
                <div class="alert alert-{{ $notif['type'] }} alert-dismissible fade show shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas {{ $notif['icon'] }} fa-lg me-3"></i>
                        <div class="flex-grow-1">
                            <strong>{{ $notif['message'] }}</strong>
                            @if(isset($notif['tasks']) && $notif['tasks']->count() > 0)
                                <ul class="mb-0 mt-1 small">
                                    @foreach($notif['tasks']->take(3) as $task)
                                        <li>{{ $task->title }} 
                                            @if($task->deadline)
                                                (Deadline: {{ $task->deadline->format('d M Y') }})
                                            @endif
                                        </li>
                                    @endforeach
                                    @if($notif['tasks']->count() > 3)
                                        <li class="text-muted">... dan {{ $notif['tasks']->count() - 3 }} tugas lainnya</li>
                                    @endif
                                </ul>
                            @endif
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endforeach
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                <div class="card-body text-center p-4">
                    <div class="mb-2"><i class="fas fa-tasks fa-2x text-info"></i></div>
                    <h3 class="fw-bold text-info mb-1">{{ $tasksBaru->count() }}</h3>
                    <small class="text-muted">Tugas Baru</small>
                    @if($tasksBaru->count() > 0)
                        <br><small class="text-info fw-semibold"><i class="fas fa-arrow-up"></i> Menunggu dikerjakan</small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
                <div class="card-body text-center p-4">
                    <div class="mb-2"><i class="fas fa-spinner fa-spin fa-2x text-warning"></i></div>
                    <h3 class="fw-bold text-warning mb-1">{{ $tasksSedangDikerjakan->count() }}</h3>
                    <small class="text-muted">Sedang Dikerjakan</small>
                    @if($tasksSedangDikerjakan->count() > 0)
                        <br><small class="text-warning fw-semibold"><i class="fas fa-users"></i> Tim sedang bekerja</small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                <div class="card-body text-center p-4">
                    <div class="mb-2"><i class="fas fa-check-circle fa-2x text-success"></i></div>
                    <h3 class="fw-bold text-success mb-1">{{ $tasksSelesai->count() }}</h3>
                    <small class="text-muted">Tugas Selesai</small>
                    @if($tasksSelesai->count() > 0)
                        <br><small class="text-success fw-semibold"><i class="fas fa-trophy"></i> Kerja bagus!</small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3" id="taskTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="baru-tab" data-bs-toggle="tab" data-bs-target="#baru" type="button" role="tab">
                <i class="fas fa-tasks me-2"></i>Baru ({{ $tasksBaru->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="progress-tab" data-bs-toggle="tab" data-bs-target="#progress" type="button" role="tab">
                <i class="fas fa-spinner me-2"></i>Sedang Dikerjakan ({{ $tasksSedangDikerjakan->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="selesai-tab" data-bs-toggle="tab" data-bs-target="#selesai" type="button" role="tab">
                <i class="fas fa-check-circle me-2"></i>Selesai ({{ $tasksSelesai->count() }})
            </button>
        </li>
    </ul>

    <div class="tab-content" id="taskTabsContent">
        <div class="tab-pane fade show active" id="baru" role="tabpanel">
            @if($tasksBaru->count() > 0)
                <div class="row g-3">
                    @foreach($tasksBaru as $task)
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100 border-info border-opacity-25">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">{{ $task->title }}</h5>
                                        <span class="badge bg-info">{{ ucfirst($task->status) }}</span>
                                    </div>
                                    @if($task->description)
                                        <p class="card-text text-muted small mb-2">{{ Str::limit($task->description, 100) }}</p>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            @if($task->priority)
                                                <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            @endif
                                            @if($task->deadline)
                                                <span class="badge bg-{{ now()->greaterThan($task->deadline) ? 'danger' : 'secondary' }} ms-1">
                                                    <i class="far fa-calendar me-1"></i>{{ $task->deadline->format('d M') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="btn-group">
                                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-tasks fa-4x text-muted opacity-25 mb-3"></i>
                    <p class="text-muted">Belum ada tugas baru</p>
                    <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Buat Tugas Pertama
                    </a>
                </div>
            @endif
        </div>

        <div class="tab-pane fade" id="progress" role="tabpanel">
            @if($tasksSedangDikerjakan->count() > 0)
                <div class="row g-3">
                    @foreach($tasksSedangDikerjakan as $task)
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100 border-warning border-opacity-25">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">{{ $task->title }}</h5>
                                        <span class="badge bg-warning text-dark">{{ ucfirst($task->status) }}</span>
                                    </div>
                                    @if($task->description)
                                        <p class="card-text text-muted small mb-2">{{ Str::limit($task->description, 100) }}</p>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            @if($task->user)
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-user me-1"></i>{{ $task->user->name }}
                                                </span>
                                            @endif
                                            @if($task->deadline)
                                                <span class="badge bg-{{ now()->greaterThan($task->deadline) ? 'danger' : 'secondary' }} ms-1">
                                                    <i class="far fa-calendar me-1"></i>{{ $task->deadline->format('d M') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="btn-group">
                                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-spinner fa-4x text-muted opacity-25 mb-3"></i>
                    <p class="text-muted">Belum ada tugas yang sedang dikerjakan</p>
                </div>
            @endif
        </div>

        <div class="tab-pane fade" id="selesai" role="tabpanel">
            @if($tasksSelesai->count() > 0)
                <div class="row g-3">
                    @foreach($tasksSelesai as $task)
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100 border-success border-opacity-25 bg-success bg-opacity-10">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0 text-decoration-line-through opacity-75">{{ $task->title }}</h5>
                                        <span class="badge bg-success">Selesai</span>
                                    </div>
                                    @if($task->description)
                                        <p class="card-text text-muted small mb-2">{{ Str::limit($task->description, 100) }}</p>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            @if($task->user)
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-user me-1"></i>{{ $task->user->name }}
                                                </span>
                                            @endif
                                            @if($task->deadline)
                                                <span class="badge bg-success ms-1">
                                                    <i class="fas fa-check me-1"></i>{{ $task->deadline->format('d M Y') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="btn-group">
                                            <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-muted opacity-25 mb-3"></i>
                    <p class="text-muted">Belum ada tugas yang selesai</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection