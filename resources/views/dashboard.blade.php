@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-3">
    <!-- Header -->
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

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <!-- Card 1: Tugas Selesai -->
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card border-0 shadow-lg rounded-4 h-100 overflow-hidden transition-all hover:shadow-xl">
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-75 mb-1 fw-medium">Tugas Selesai</p>
                            <h2 class="text-white fw-bold mb-0">
                                {{ is_object($completedTasks) ? $completedTasks->count() : ($completedTasks ?? 0) }}
                            </h2>
                        </div>
                        <div class="bg-emerald-100 text-emerald-700 p-3 rounded-circle">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4 pb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">
                            Dari 
                            {{ is_object($totalTasks) ? $totalTasks->count() : ($totalTasks ?? 0) }}
                        </span>
                        <span class="badge bg-emerald-100 text-emerald-800 fw-semibold">
                            {{
                                ($totalTasks ?? 0) > 0 
                                    ? round(
                                        (is_object($completedTasks) ? $completedTasks->count() : ($completedTasks ?? 0)) 
                                        / ($totalTasks ?? 0) * 100
                                      ) 
                                    : 0
                            }}%
                        </span>
                    </div>
                    <div class="progress rounded-pill" style="height: 6px;">
                        <div class="progress-bar bg-emerald-500" role="progressbar"
                             style="width: {{
                                ($totalTasks ?? 0) > 0 
                                    ? ((is_object($completedTasks) ? $completedTasks->count() : ($completedTasks ?? 0)) / ($totalTasks ?? 0)) * 100 
                                    : 0
                             }}%;"
                             aria-valuenow="{{ 
                                ($totalTasks ?? 0) > 0 
                                    ? round(((is_object($completedTasks) ? $completedTasks->count() : ($completedTasks ?? 0)) / ($totalTasks ?? 0)) * 100) 
                                    : 0 
                             }}"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Progres Keseluruhan -->
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card border-0 shadow-lg rounded-4 h-100 overflow-hidden transition-all hover:shadow-xl">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-75 mb-1 fw-medium">Progres Keseluruhan</p>
                            <h2 class="text-white fw-bold mb-0">
                                {{ is_numeric($progressPercentage) ? $progressPercentage : 0 }}%
                            </h2>
                        </div>
                        <div class="bg-blue-100 text-blue-700 p-3 rounded-circle">
                            <i class="fas fa-chart-line fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4 pb-3">
                    <div class="progress rounded-pill" style="height: 8px;">
                        <div class="progress-bar bg-blue-500 rounded-pill" role="progressbar"
                             style="width: {{ is_numeric($progressPercentage) ? $progressPercentage : 0 }}%;"
                             aria-valuenow="{{ is_numeric($progressPercentage) ? $progressPercentage : 0 }}"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Tugas Terlambat -->
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card border-0 shadow-lg rounded-4 h-100 overflow-hidden transition-all hover:shadow-xl {{ (is_object($overdueTasks) ? $overdueTasks->count() : ($overdueTasks ?? 0)) > 0 ? 'border-start border-4 border-warning' : '' }}">
                <div class="bg-gradient-to-r from-amber-500 to-amber-600 p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-75 mb-1 fw-medium">Tugas Terlambat</p>
                            <h2 class="text-white fw-bold mb-0">
                                {{ is_object($overdueTasks) ? $overdueTasks->count() : ($overdueTasks ?? 0) }}
                            </h2>
                        </div>
                        <div class="bg-amber-100 text-amber-700 p-3 rounded-circle">
                            <i class="fas fa-exclamation-triangle fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4 pb-3">
                    @if((is_object($overdueTasks) ? $overdueTasks->count() : ($overdueTasks ?? 0)) > 0)
                        <a href="{{ route('tasks.overdue') }}" class="btn btn-sm btn-outline-warning text-warning fw-medium">
                            <i class="fas fa-eye me-1"></i> Lihat Detail
                        </a>
                    @else
                        <span class="text-success fw-medium"><i class="fas fa-check-circle me-1"></i> Semua on-track</span>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- Overdue Alert -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="fw-bold text-dark mb-3">
                <i class="fas fa-clock text-warning me-2"></i>Tugas Terlambat (Overdue)
            </h3>

            @if((is_object($overdueTasks) ? $overdueTasks->count() : ($overdueTasks ?? 0)) > 0)
                <div class="alert alert-warning border-0 rounded-4 p-4 bg-white shadow-sm border-start border-4 border-warning">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i class="fas fa-exclamation-triangle text-warning fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-warning mb-1">
                                Perhatian! 
                                {{ is_object($overdueTasks) ? $overdueTasks->count() : ($overdueTasks ?? 0) }} 
                                tugas melewati tenggat waktu
                            </h5>
                            <p class="text-muted mb-2">Prioritaskan tugas ini untuk menjaga jadwal proyek.</p>
                            <a href="{{ route('tasks.overdue') }}" class="btn btn-warning btn-sm">
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
</div>
@endsection

@section('styles')
<style>
    .bg-gradient-to-r {
        background: linear-gradient(90deg, var(--bs-gradient-start), var(--bs-gradient-end));
    }
    .from-emerald-500 { --bs-gradient-start: #10b981; }
    .to-emerald-600 { --bs-gradient-end: #059669; }
    .from-blue-500 { --bs-gradient-start: #3b82f6; }
    .to-blue-600 { --bs-gradient-end: #2563eb; }
    .from-amber-500 { --bs-gradient-start: #f59e0b; }
    .to-amber-600 { --bs-gradient-end: #d97706; }

    .transition-all { transition: all 0.3s ease-in-out; }
    .hover\:shadow-xl:hover {
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04) !important;
    }
    .badge.bg-emerald-100 { color: #059669; background-color: #ecfdf5; }
    .badge.bg-amber-100 { color: #d97706; background-color: #fffbeb; }
    .border-warning { border-left-color: #f59e0b !important; }
    .border-success { border-left-color: #10b981 !important; }
</style>
@endsection