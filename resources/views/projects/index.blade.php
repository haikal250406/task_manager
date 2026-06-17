@extends('layouts.app')

@section('content')
<div class="container py-2">
    
    <div class="row align-items-center mb-4 g-3">
        <div class="col-md-5">
            <h2 class="fw-bold text-dark mb-1">Daftar Proyek</h2>
            <p class="text-muted small mb-0">Kelola dan pantau seluruh proyek aktif kelompok Anda</p>
        </div>
        <div class="col-md-7">
            <form action="{{ route('projects.index') }}" method="GET" class="d-flex flex-wrap justify-content-md-end gap-2">
                <div class="input-group" style="max-width: 300px;">
                    <input type="text" name="search" class="form-control bg-white border-1 rounded-3" placeholder="Cari proyek..." value="{{ $search ?? '' }}">
                    <button type="submit" class="btn btn-outline-secondary rounded-3 px-3">Cari</button>
                </div>
                <a href="{{ route('projects.create') }}" class="btn btn-primary rounded-3 fw-semibold shadow-sm px-3">
                    + Buat Proyek Baru
                </a>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4" role="alert">
            🎉 {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        @if($projects->isEmpty())
            <div class="col-12">
                <div class="alert alert-warning border-0 shadow-sm rounded-4 text-center p-5 bg-white">
                    <span class="display-4 d-block mb-3">📁</span>
                    <h5 class="fw-bold text-dark">Tidak Ada Proyek Ditemukan</h5>
                    <p class="text-muted small mb-0">Silakan buat proyek baru atau bersihkan kembali kolom pencarian Anda.</p>
                </div>
            </div>
        @else
            @foreach ($projects as $project)
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                        <div class="card-body p-4 d-flex flex-column justify-content-between">
                            
                            <div class="mb-4">
                                <h4 class="fw-bold text-dark mb-2">{{ $project->name }}</h4>
                                <p class="text-muted small mb-0" style="min-height: 40px;">
                                    {{ \Illuminate\Support\Str::limit($project->description, 80, '...') ?? 'Tidak ada deskripsi proyek.' }}
                                </p>
                            </div>
                            
                            <div>
                                <div class="mb-3">
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-2 fw-semibold small">
                                        📋 {{ $project->tasks_count }} Tugas Terdaftar
                                    </span>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-outline-primary rounded-3 flex-grow-1 py-2 fw-semibold">
                                        Lihat Detail
                                    </a>
                                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus proyek ini?');" class="flex-grow-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 w-100 py-2 fw-semibold">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

</div>
@endsection