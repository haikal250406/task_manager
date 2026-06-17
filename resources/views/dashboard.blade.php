@extends('layouts.app')

@section('content')
<div class="container py-2">
    
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h2 class="fw-bold text-dark mb-1">Dasbor Statistik</h2>
            <p class="text-muted small mb-0">Pantau perkembangan proyek dan tugas Anda secara real-time</p>
        </div>
        <a href="{{ route('projects.index') }}" class="btn btn-dark rounded-3 px-4 py-2 fw-semibold shadow-sm">
            Buka Daftar Proyek &rarr;
        </a>
    </div>

    <div class="row g-4 mb-5">
        
        <div class="col-md-6">
            <div class="card bg-success border-0 shadow rounded-4 text-white p-3 h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="mb-3">
                        <h5 class="card-title fw-semibold opacity-75 mb-1">Tugas Selesai</h5>
                        <hr class="opacity-25 my-2">
                    </div>
                    <div class="d-flex align-items-baseline">
                        <h1 class="display-3 fw-bold mb-0">{{ $completedTasks }}</h1>
                        <span class="fs-3 opacity-50 mx-2">/</span>
                        <h3 class="fw-semibold opacity-75 mb-0">{{ $totalTasks }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-primary border-0 shadow rounded-4 text-white p-3 h-100" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="mb-3">
                        <h5 class="card-title fw-semibold opacity-75 mb-1">Progres Keseluruhan</h5>
                        <hr class="opacity-25 my-2">
                    </div>
                    <div>
                        <h1 class="display-3 fw-bold mb-3">{{ $progressPercentage }}%</h1>
                        <div class="progress bg-white bg-opacity-25 rounded-pill" style="height: 12px;">
                            <div class="progress-bar bg-white rounded-pill shadow-sm" role="progressbar" style="width: {{ $progressPercentage }}%;" aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center mb-3">
                <span class="fs-4 me-2">⚠️</span>
                <h4 class="fw-bold text-danger mb-0">Peringatan Tugas Terlambat (Overdue)</h4>
            </div>
            
            <div class="alert alert-success border-0 shadow-sm rounded-3 p-4 d-flex align-items-center bg-white" style="border-left: 5px solid #198754 !important;">
                <span class="fs-3 me-3">🎉</span>
                <div>
                    <h6 class="fw-bold text-success mb-1">Kerja bagus!</h6>
                    <p class="text-muted small mb-0">Tidak ada tugas yang melewati tenggat waktu saat ini.</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection