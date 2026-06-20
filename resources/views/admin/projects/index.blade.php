@extends('layouts.app')

@section('title', 'Daftar Proyek')

@section('content')
<div class="container-fluid py-4 px-3">
    <!-- Header -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3 mb-4">
        <div>
            <h1 class="display-5 fw-bold text-dark">Daftar Proyek</h1>
            <p class="text-muted mb-0">Kelola semua proyek tim Anda dalam satu tempat</p>
        </div>
        <a href="{{ route('projects.create') }}" class="btn btn-primary rounded-3 px-4 py-2 fw-medium shadow-sm">
            <i class="fas fa-plus me-2"></i> Buat Proyek Baru
        </a>
    </div>

    <!-- Filter & Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('projects.index') }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari nama atau deskripsi proyek..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>Ditangguhkan</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Projects Grid -->
    @if($projects->count() > 0)
        <div class="row g-4">
            @foreach($projects as $project)
                <div class="col-xl-4 col-lg-6">
                    <div class="card border-0 shadow-sm h-100 project-card">
                        <div class="card-body p-4">
                            <!-- Header Project -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                        <i class="fas fa-project-diagram text-primary fa-lg"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-dark">{{ $project->name }}</h5>
                                        <small class="text-muted">oleh {{ $project->user->name ?? 'Unknown' }}</small>
                                    </div>
                                </div>
                                <span class="badge {{ $project->getStatusBadgeClass() }}">
                                    {{ $project->getStatusText() }}
                                </span>
                            </div>

                            <!-- Description -->
                            <p class="text-muted mb-3" style="min-height: 40px;">
                                {{ Str::limit($project->description, 100) ?? 'Tidak ada deskripsi' }}
                            </p>

                            <!-- Task Stats -->
                            @php $stats = $project->getTaskStats(); @endphp
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-tasks me-1"></i> {{ $stats['total'] }} Tugas
                                    </small>
                                    <small class="fw-bold text-primary">{{ $project->getProgressPercentage() }}%</small>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ $stats['total'] > 0 ? ($stats['completed'] / $stats['total']) * 100 : 0 }}%"
                                         title="Selesai: {{ $stats['completed'] }}"></div>
                                    <div class="progress-bar bg-warning" role="progressbar" 
                                         style="width: {{ $stats['total'] > 0 ? ($stats['inProgress'] / $stats['total']) * 100 : 0 }}%"
                                         title="In Progress: {{ $stats['inProgress'] }}"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="text-success"><i class="fas fa-check me-1"></i>{{ $stats['completed'] }}</small>
                                    <small class="text-warning"><i class="fas fa-spinner me-1"></i>{{ $stats['inProgress'] }}</small>
                                    <small class="text-secondary"><i class="fas fa-clock me-1"></i>{{ $stats['todo'] }}</small>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i>{{ $project->created_at->diffForHumans() }}
                                </small>
                                <div class="btn-group">
                                    <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus proyek {{ $project->name }}?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($projects->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $projects->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-folder-open fa-4x text-muted opacity-25 mb-3"></i>
                <h4 class="text-muted">Belum ada proyek</h4>
                <p class="text-muted mb-4">Mulai buat proyek pertama Anda untuk mengelola tugas dengan lebih baik</p>
                <a href="{{ route('projects.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Buat Proyek Pertama
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .project-card {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .project-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection