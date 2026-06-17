@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="mb-3">
                <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-outline-secondary rounded-3 fw-semibold">
                    &larr; Batal & Kembali ke Papan Proyek
                </a>
            </div>

            <div class="card bg-white rounded-4 shadow-sm overflow-hidden" style="border: 1px solid #e2e8f0 !important;">
                <div class="card-body p-4 p-md-5">
                    
                    <div class="mb-4 text-center">
                        <h3 class="fw-bold text-dark mb-1">Tambah Tugas Baru</h3>
                        <p class="text-muted small">Untuk Proyek: <span class="fw-bold text-primary">{{ $project->name }}</span></p>
                    </div>

                    <form method="POST" action="{{ route('tasks.store') }}">
                        @csrf
                        
                        <input type="hidden" name="project_id" value="{{ $project->id }}">

                        <div class="mb-4">
                            <label class="form-label text-secondary small fw-semibold">Judul Tugas <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-lg bg-white rounded-3 @error('title') is-invalid @enderror" value="{{ old('title') }}" required autofocus placeholder="Contoh: Membuat desain halaman login" style="border: 1px solid #cbd5e1 !important; font-size: 0.95rem;">
                            @error('title') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label text-secondary small fw-semibold">Jenis Tugas <span class="text-danger">*</span></label>
                                <select name="type" class="form-select form-select-lg bg-white rounded-3 @error('type') is-invalid @enderror" required style="border: 1px solid #cbd5e1 !important; font-size: 0.95rem;">
                                    <option value="" disabled selected>Pilih Jenis...</option>
                                    <option value="Feature" {{ old('type') == 'Feature' ? 'selected' : '' }}>✨ Feature (Fitur Baru)</option>
                                    <option value="Bug" {{ old('type') == 'Bug' ? 'selected' : '' }}>🐛 Bug (Perbaikan Error)</option>
                                </select>
                                @error('type') 
                                    <div class="invalid-feedback">{{ $message }}</div> 
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-semibold">Prioritas</label>
                                <select name="priority" class="form-select form-select-lg bg-white rounded-3" style="border: 1px solid #cbd5e1 !important; font-size: 0.95rem;">
                                    <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>🟢 Low (Rendah)</option>
                                    <option value="Medium" {{ old('priority') == 'Medium' ? 'selected' : '' }} selected>🟡 Medium (Sedang)</option>
                                    <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>🔴 High (Tinggi)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-secondary small fw-semibold">Tenggat Waktu (Deadline) <span class="text-danger">*</span></label>
                            <input type="date" name="deadline" class="form-control form-control-lg bg-white rounded-3 @error('deadline') is-invalid @enderror" value="{{ old('deadline') }}" required style="border: 1px solid #cbd5e1 !important; font-size: 0.95rem;">
                            @error('deadline') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>

                        <input type="hidden" name="status" value="To Do">

                        <div class="d-grid mt-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-bold shadow-sm py-2" style="font-size: 1rem;">
                                Simpan ke Papan Proyek
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection