@extends('layouts.app')

@section('title', 'Edit Proyek')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0">
                    <h4 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-edit me-2 text-warning"></i>Edit Proyek
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('projects.update', $project) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                Nama Proyek <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="name" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $project->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $project->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Status Proyek <span class="text-danger">*</span></label>
                            <div class="row g-2">
                                @foreach(['active' => 'Aktif', 'on_hold' => 'Ditangguhkan', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $value => $label)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" 
                                                   id="status_{{ $value }}" value="{{ $value }}" 
                                                   {{ old('status', $project->status) == $value ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_{{ $value }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4 py-2">
                                <i class="fas fa-save me-2"></i> Update Proyek
                            </button>
                            <a href="{{ route('projects.index') }}" class="btn btn-secondary px-4 py-2">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection