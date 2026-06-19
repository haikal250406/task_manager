@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-3">
    @php
        // === Normalisasi variabel dari controller (fallback ke 0 jika tidak ada) ===
        $totalCount = $totalTasksCount ?? $totalTasks ?? 0;
        $completedCount = $completedTasksCount ?? $completedTasks ?? 0;
        $overdueCount = $overdueCount ?? (
            is_countable($overdueTasks) ? count($overdueTasks) :
            (is_object($overdueTasks) && method_exists($overdueTasks, 'count') ? $overdueTasks->count() : 0)
        );
        $activeProjectsCount = $activeProjectsCount ?? $activeProjects ?? 0;
        $progressPercentage = $progressPercentage ?? 0;

        // === Hitung turunan untuk UI ===
        $pendingCount = max(0, $totalCount - $completedCount);
        $onTrackCount = max(0, $activeProjectsCount - $overdueCount);
        $completedPercentage = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
        $pendingPercentage = $totalCount > 0 ? round(($pendingCount / $totalCount) * 100) : 0;
        $onTrackPercentage = $activeProjectsCount > 0 ? round(($onTrackCount / $activeProjectsCount) * 100) : 0;
        $overduePercentage = $activeProjectsCount > 0 ? round(($overdueCount / $activeProjectsCount) * 100) : 0;
    @endphp

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
            <a href="{{ route('projects.index') }}" class="btn btn-outline-primary rounded-3 px-4 py-2 fw-medium shadow-sm d-flex align-items-center">
                <i class="fas fa-plus-circle me-2"></i> Tambah Tugas
            </a>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="row g-4 mb-5">
        <!-- Card 1: Total Tugas -->
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-lg rounded-4 h-100 overflow-hidden transition-all hover:shadow-xl">
                <div class="bg-gradient-to-r from-slate-700 to-slate-800 p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-75 mb-1 fw-medium">Total Tugas</p>
                            <h2 class="text-white fw-bold mb-0">{{ $totalCount }}</h2>
                        </div>
                        <div class="bg-slate-200 text-slate-800 p-3 rounded-circle">
                            <i class="fas fa-tasks fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4 pb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-success bg-opacity-10 text-success small px-2 py-1">Selesai: {{ $completedCount }}</span>
                        <span class="badge bg-warning bg-opacity-10 text-warning small px-2 py-1">Pending: {{ $pendingCount }}</span>
                    </div>
                    <div class="progress rounded-pill" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $completedPercentage }}%" aria-valuenow="{{ $completedCount }}" aria-valuemin="0" aria-valuemax="{{ $totalCount }}"></div>
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pendingPercentage }}%" aria-valuenow="{{ $pendingCount }}" aria-valuemin="0" aria-valuemax="{{ $totalCount }}"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Progres Keseluruhan -->
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-lg rounded-4 h-100 overflow-hidden transition-all hover:shadow-xl">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-75 mb-1 fw-medium">Progres Keseluruhan</p>
                            <h2 class="text-white fw-bold mb-0">{{ $progressPercentage }}%</h2>
                        </div>
                        <div class="bg-blue-100 text-blue-700 p-3 rounded-circle">
                            <i class="fas fa-chart-line fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4 pb-3">
                    <div class="progress rounded-pill" style="height: 8px;">
                        <div class="progress-bar bg-blue-500 rounded-pill" role="progressbar"
                             style="width: {{ $progressPercentage }}%;"
                             aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Tugas Terlambat -->
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-lg rounded-4 h-100 overflow-hidden transition-all hover:shadow-xl {{ $overdueCount > 0 ? 'border-start border-4 border-warning' : '' }}">
                <div class="bg-gradient-to-r from-amber-500 to-amber-600 p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-75 mb-1 fw-medium">Tugas Terlambat</p>
                            <h2 class="text-white fw-bold mb-0">{{ $overdueCount }}</h2>
                        </div>
                        <div class="bg-amber-100 text-amber-700 p-3 rounded-circle">
                            <i class="fas fa-exclamation-triangle fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4 pb-3">
                    @if($overdueCount > 0)
                        <a href="{{ route('tasks.overdue') }}" class="btn btn-sm btn-outline-warning text-warning fw-medium">
                            <i class="fas fa-eye me-1"></i> Lihat Detail
                        </a>
                    @else
                        <span class="text-success fw-medium"><i class="fas fa-check-circle me-1"></i> Semua on-track</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Card 4: Proyek Aktif -->
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-lg rounded-4 h-100 overflow-hidden transition-all hover:shadow-xl">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-75 mb-1 fw-medium">Proyek Aktif</p>
                            <h2 class="text-white fw-bold mb-0">{{ $activeProjectsCount }}</h2>
                        </div>
                        <div class="bg-indigo-100 text-indigo-700 p-3 rounded-circle">
                            <i class="fas fa-cube fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4 pb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-success bg-opacity-10 text-success small px-2 py-1">On Track: {{ $onTrackCount }}</span>
                        <span class="badge bg-danger bg-opacity-10 text-danger small px-2 py-1">Overdue: {{ $overdueCount }}</span>
                    </div>
                    <div class="progress rounded-pill" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $onTrackPercentage }}%" aria-valuenow="{{ $onTrackCount }}" aria-valuemin="0" aria-valuemax="{{ $activeProjectsCount }}"></div>
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $overduePercentage }}%" aria-valuenow="{{ $overdueCount }}" aria-valuemin="0" aria-valuemax="{{ $activeProjectsCount }}"></div>
                    </div>
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

            @if($overdueCount > 0)
                <div class="alert alert-warning border-0 rounded-4 p-4 bg-white shadow-sm border-start border-4 border-warning">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i class="fas fa-exclamation-triangle text-warning fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-warning mb-1">
                                Perhatian! {{ $overdueCount }} tugas melewati tenggat waktu
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

    <!-- Recent Projects & Tasks -->
    <div class="row">
        <!-- Recent Projects -->
        <div class="col-xl-6 mb-4 mb-xl-0">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-0">
                    <h4 class="h5 mb-0 fw-bold text-dark">
                        <i class="fas fa-history me-2 text-primary"></i>Proyek Terbaru
                    </h4>
                </div>
                <div class="card-body">
                    @if(isset($recentProjects) && $recentProjects->isNotEmpty())
                        <ul class="list-group list-group-flush">
                            @foreach($recentProjects as $project)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $project->name ?? 'Proyek Tanpa Nama' }}</h6>
                                        <small class="text-muted">{{ $project->updated_at->diffForHumans() ?? 'Tanggal tidak diketahui' }}</small>
                                    </div>
                                    <span class="badge {{ $project->status === 'active' ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                                        {{ $project->status ?? 'Status Tidak Diketahui' }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted text-center my-4">Belum ada proyek terbaru.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Tasks -->
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-0">
                    <h4 class="h5 mb-0 fw-bold text-dark">
                        <i class="fas fa-list-check me-2 text-primary"></i>Tugas Terbaru
                    </h4>
                </div>
                <div class="card-body">
                    @if(isset($recentTasks) && $recentTasks->isNotEmpty())
                        <ul class="list-group list-group-flush">
                            @foreach($recentTasks as $task)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $task->title ?? 'Tugas Tanpa Judul' }}</h6>
                                        <small class="text-muted">
                                            {{ $task->project->name ?? 'Tanpa Proyek' }} • 
                                            {{ $task->updated_at->diffForHumans() ?? 'Tanggal tidak diketahui' }}
                                        </small>
                                    </div>
                                    <span class="badge {{ $task->status === 'done' ? 'bg-success' : ($task->status === 'in_progress' ? 'bg-warning' : 'bg-secondary') }} rounded-pill">
                                        {{ $task->status ?? 'Status Tidak Diketahui' }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted text-center my-4">Belum ada tugas terbaru.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    /* Gradien */
    .bg-gradient-to-r {
        background: linear-gradient(90deg, var(--bs-gradient-start), var(--bs-gradient-end));
    }
    .from-slate-700 { --bs-gradient-start: #334155; }
    .to-slate-800 { --bs-gradient-end: #1e293b; }
    .from-blue-500 { --bs-gradient-start: #3b82f6; }
    .to-blue-600 { --bs-gradient-end: #2563eb; }
    .from-amber-500 { --bs-gradient-start: #f59e0b; }
    .to-amber-600 { --bs-gradient-end: #d97706; }
    .from-indigo-500 { --bs-gradient-start: #6366f1; }
    .to-indigo-600 { --bs-gradient-end: #4f46e5; }

    /* Transisi & Hover */
    .transition-all { transition: all 0.3s ease-in-out; }
    .hover\:shadow-xl:hover {
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04) !important;
    }

    /* Badge */
    .badge.bg-success.bg-opacity-10 { color: #15803d; background-color: rgba(21, 128, 61, 0.1); }
    .badge.bg-warning.bg-opacity-10 { color: #a16207; background-color: rgba(161, 98, 7, 0.1); }
    .badge.bg-danger.bg-opacity-10 { color: #b91c1c; background-color: rgba(185, 28, 28, 0.1); }

    /* Border left */
    .border-warning { border-left-color: #f59e0b !important; }
    .border-success { border-left-color: #10b981 !important; }
</style>
@endsection

@section('scripts')
<script>
    // Animasi kartu saat dimuat
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease-in-out, transform 0.5s ease-in-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endsection