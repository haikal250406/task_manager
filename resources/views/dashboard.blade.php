@extends('layouts.app')

@section('content')
@php
    // === NORMALISASI DATA (Mencegah Error Collection vs Integer) ===
    $getCount = function($var) {
        if (is_numeric($var)) return (int)$var;
        if (is_object($var) && method_exists($var, 'count')) return $var->count();
        return 0;
    };

    $totalTasks = $getCount($totalTasks ?? null);
    $completedTasks = $getCount($completedTasks ?? null);
    $overdueTasks = $getCount($overdueTasks ?? null);
    $activeProjects = $getCount($activeProjects ?? null);

    // === HITUNG DATA TAMBAHAN UNTUK UI ===
    $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
    $pendingTasks = max(0, $totalTasks - $completedTasks);
    $onTrackProjects = $activeProjects;
    $overdueProjects = 0;
@endphp

<style>
    /* Global Base Rule untuk Progress di dalam Card */
    .progress {
        background-color: rgba(255, 255, 255, 0.6) !important;
        border: none;
    }

    /* 1. Card Total Tugas (Tema Biru Indigo) */
    .stat-card-tasks {
        background: linear-gradient(135deg, #E0E7FF 0%, #C7D2FE 100%) !important;
    }
    .stat-card-tasks .card-label { color: #3730A3 !important; }
    .stat-card-tasks .card-value { color: #1E1B4B !important; }
    .stat-card-tasks .icon-circle { color: #4F46E5 !important; background-color: #FFFFFF !important; }
    .stat-card-tasks .progress-bar { background-color: #4F46E5 !important; }

    /* 2. Card Progres Keseluruhan (Tema Hijau Emerald) */
    .stat-card-progress {
        background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%) !important;
    }
    .stat-card-progress .card-label { color: #065F46 !important; }
    .stat-card-progress .card-value { color: #064E3B !important; }
    .stat-card-progress .icon-circle { color: #10B981 !important; background-color: #FFFFFF !important; }
    .stat-card-progress .progress-bar { background-color: #10B981 !important; }

    /* 3. Card Tugas Terlambat (Tema Merah / Soft Red) */
    .stat-card-overdue {
        background: linear-gradient(135deg, #FEE2E2 0%, #FCA5A5 100%) !important;
    }
    .stat-card-overdue .card-label { color: #991B1B !important; }
    .stat-card-overdue .card-value { color: #7F1D1D !important; }
    .stat-card-overdue .icon-circle { color: #EF4444 !important; background-color: #FFFFFF !important; }
    .text-danger-custom { color: #DC2626 !important; }
    .text-success-custom { color: #059669 !important; }
    .border-danger-custom { border-left-color: #EF4444 !important; border-left-width: 4px !important; }

    /* 4. Card Proyek Aktif (Tema Ungu Soft) */
    .stat-card-projects {
        background: linear-gradient(135deg, #F3E8FF 0%, #E9D5FF 100%) !important;
    }
    .stat-card-projects .card-label { color: #6B21A8 !important; }
    .stat-card-projects .card-value { color: #581C87 !important; }
    .stat-card-projects .icon-circle { color: #9333EA !important; background-color: #FFFFFF !important; }
    .stat-card-projects .progress-bar { background-color: #9333EA !important; }

    /* Efek Hover Animasi Angkat */
    .stat-card-tasks:hover,
    .stat-card-progress:hover,
    .stat-card-overdue:hover,
    .stat-card-projects:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
    }
    
    .stat-card-tasks, .stat-card-progress, .stat-card-overdue, .stat-card-projects {
        transition: all 0.3s ease-in-out !important;
    }

    /* Badge Custom Opacity */
    .badge.bg-success.bg-opacity-10 { 
        color: #047857 !important; 
        background-color: rgba(4, 120, 87, 0.2) !important; 
    }
    .badge.bg-warning.bg-opacity-10 { 
        color: #B45309 !important; 
        background-color: rgba(180, 83, 9, 0.2) !important; 
    }
    .badge.bg-danger.bg-opacity-10 { 
        color: #DC2626 !important; 
        background-color: rgba(220, 38, 38, 0.2) !important; 
    }
</style>

<div class="container-fluid py-4 px-3">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3 mb-5">
        <div>
            <h1 class="display-5 fw-bold text-dark">Dashboard Proyek</h1>
            <p class="text-muted mb-0">Pantau perkembangan, prioritas, dan kesehatan proyek secara real-time.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('projects.index') }}" class="btn btn-dark rounded-3 px-4 py-2 fw-medium shadow-sm d-flex align-items-center">
                <i class="fas fa-project-diagram me-2"></i> Daftar Proyek
            </a>
            <a href="{{ route('tasks.create') }}" class="btn btn-outline-primary rounded-3 px-4 py-2 fw-medium shadow-sm d-flex align-items-center">
                <i class="fas fa-plus-circle me-2"></i> Tambah Tugas
            </a>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden stat-card-tasks">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="card-label mb-1 fw-semibold small text-uppercase">Total Tugas</p>
                            <h2 class="card-value fw-bold mb-0 display-6">{{ $totalTasks }}</h2>
                        </div>
                        <div class="icon-circle p-3 rounded-circle">
                            <i class="fas fa-tasks fa-lg"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-success bg-opacity-10 text-success small px-2 py-1">
                            <i class="fas fa-check me-1"></i>Selesai: {{ $completedTasks }}
                        </span>
                        <span class="badge bg-warning bg-opacity-10 text-warning small px-2 py-1">
                            <i class="fas fa-clock me-1"></i>Pending: {{ $pendingTasks }}
                        </span>
                    </div>
                    <div class="progress rounded-pill" style="height: 6px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden stat-card-progress">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="card-label mb-1 fw-semibold small text-uppercase">Progres Keseluruhan</p>
                            <h2 class="card-value fw-bold mb-0 display-6">{{ $progressPercentage }}%</h2>
                        </div>
                        <div class="icon-circle p-3 rounded-circle">
                            <i class="fas fa-chart-line fa-lg"></i>
                        </div>
                    </div>
                    <div class="progress rounded-pill mb-2" style="height: 8px;">
                        <div class="progress-bar rounded-pill" role="progressbar" style="width: {{ $progressPercentage }}%;"></div>
                    </div>
                    <small class="card-label d-block fw-semibold">
                        {{ $completedTasks }} dari {{ $totalTasks }} tugas selesai
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden stat-card-overdue {{ $overdueTasks > 0 ? 'border-start border-4 border-danger-custom' : '' }}">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="card-label mb-1 fw-semibold small text-uppercase">Tugas Terlambat</p>
                            <h2 class="card-value fw-bold mb-0 display-6">{{ $overdueTasks }}</h2>
                        </div>
                        <div class="icon-circle p-3 rounded-circle">
                            <i class="fas fa-exclamation-triangle fa-lg"></i>
                        </div>
                    </div>
                    @if($overdueTasks > 0)
                        <span class="text-danger-custom fw-bold">
                            <i class="fas fa-exclamation-circle me-1"></i>Perlu perhatian
                        </span>
                    @else
                        <span class="text-success-custom fw-bold">
                            <i class="fas fa-check-circle me-1"></i>Semua on-track
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden stat-card-projects">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="card-label mb-1 fw-semibold small text-uppercase">Proyek Aktif</p>
                            <h2 class="card-value fw-bold mb-0 display-6">{{ $activeProjects }}</h2>
                        </div>
                        <div class="icon-circle p-3 rounded-circle">
                            <i class="fas fa-cube fa-lg"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-success bg-opacity-10 text-success small px-2 py-1">
                            On Track: {{ $onTrackProjects }}
                        </span>
                        <span class="badge bg-danger bg-opacity-10 text-danger small px-2 py-1">
                            Overdue: {{ $overdueProjects }}
                        </span>
                    </div>
                    <div class="progress rounded-pill" style="height: 6px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $activeProjects > 0 ? ($onTrackProjects / $activeProjects) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <h3 class="fw-bold text-dark mb-3">
                <i class="fas fa-clock text-warning me-2"></i>Tugas Terlambat (Overdue)
            </h3>

            @if($overdueTasks > 0)
                <div class="alert alert-warning border-0 rounded-4 p-4 bg-white shadow-sm border-start border-4 border-warning">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i class="fas fa-exclamation-triangle text-warning fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-warning mb-1">
                                Perhatian! {{ $overdueTasks }} tugas melewati tenggat waktu
                            </h5>
                            <p class="text-muted mb-2">Prioritaskan tugas ini untuk menjaga jadwal proyek.</p>
                            <a href="{{ route('tasks.index') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-bell me-1"></i> Kelola Tugas Terlambat
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-success border-0 rounded-4 p-4 bg-white shadow-sm border-start border-4 border-success">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i class="fas fa-thumbs-up text-success fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-success mb-1">Semua tugas on-track!</h5>
                            <p class="text-muted mb-0">Tidak ada tugas yang melewati tenggat waktu saat ini. Kerja bagus!</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 mb-4 mb-xl-0">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-0">
                    <h4 class="h5 mb-0 fw-bold text-dark">
                        <i class="fas fa-history me-2 text-primary"></i>Proyek Terbaru
                    </h4>
                </div>
                <div class="card-body">
                    @if(isset($recentProjects) && $recentProjects->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($recentProjects as $project)
                                <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                    <div>
                                        <h6 class="mb-1 fw-semibold text-dark">{{ $project->name ?? 'Proyek Tanpa Nama' }}</h6>
                                        <small class="text-muted">
                                            <i class="far fa-clock me-1"></i>{{ $project->updated_at->diffForHumans() ?? 'Baru saja' }}
                                        </small>
                                    </div>
                                    <span class="badge {{ ($project->status ?? '') === 'active' ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3 py-2">
                                        {{ ucfirst($project->status ?? 'draft') }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                            <p>Belum ada proyek. <a href="{{ route('projects.create') }}" class="text-primary">Buat proyek pertama</a></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-0">
                    <h4 class="h5 mb-0 fw-bold text-dark">
                        <i class="fas fa-list-check me-2 text-primary"></i>Tugas Terbaru
                    </h4>
                </div>
                <div class="card-body">
                    @if(isset($recentTasks) && $recentTasks->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($recentTasks as $task)
                                <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                    <div>
                                        <h6 class="mb-1 fw-semibold text-dark">{{ $task->title ?? 'Tugas Tanpa Judul' }}</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-folder me-1"></i>{{ $task->project->name ?? 'Tanpa Proyek' }} • 
                                            <i class="far fa-clock me-1"></i>{{ $task->updated_at->diffForHumans() ?? 'Baru saja' }}
                                        </small>
                                    </div>
                                    <span class="badge {{ $task->status === 'done' ? 'bg-success' : ($task->status === 'in_progress' ? 'bg-warning text-dark' : 'bg-secondary') }} rounded-pill px-3 py-2">
                                        {{ ucfirst(str_replace('_', ' ', $task->status ?? 'todo')) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-clipboard-list fa-3x mb-3 opacity-25"></i>
                            <p>Belum ada tugas. <a href="{{ route('tasks.create') }}" class="text-primary">Tambah tugas pertama</a></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection