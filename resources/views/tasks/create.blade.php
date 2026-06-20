@extends('layouts.app')

@section('title', 'Tambah Tugas Baru')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0">
                    <h4 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-plus-circle me-2 text-primary"></i>Tambah Tugas Baru
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('tasks.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">
                                Judul Tugas <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" id="title" 
                                   class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                   value="{{ old('title') }}" 
                                   placeholder="Contoh: Desain Homepage Website" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Jelaskan detail tugas...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="project_id" class="form-label fw-semibold">
                                    Proyek
                                </label>
                                <div class="input-group">
                                    <select name="project_id" id="project_id" 
                                            class="form-select @error('project_id') is-invalid @enderror">
                                        <option value="">-- Tanpa Proyek (Opsional) --</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" 
                                                    {{ old('project_id', $projectId ?? '') == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-primary" 
                                            data-bs-toggle="modal" data-bs-target="#createProjectModal"
                                            title="Buat Proyek Baru">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                @error('project_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted mt-1 d-block">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Pilih proyek atau buat proyek baru
                                </small>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="user_id" class="form-label fw-semibold">Assigned To</label>
                                <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror">
                                    <option value="">Unassigned</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="to_do" {{ old('status', 'to_do') == 'to_do' ? 'selected' : '' }}>To Do</option>
                                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>Done</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Prioritas <span class="text-danger">*</span></label>
                                <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                    <option value="low" {{ old('priority', 'medium') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="deadline" class="form-label fw-semibold">Deadline</label>
                            <input type="date" name="deadline" id="deadline" 
                                   class="form-control @error('deadline') is-invalid @enderror" 
                                   value="{{ old('deadline') }}">
                            @error('deadline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4 py-2">
                                <i class="fas fa-save me-2"></i> Simpan Tugas
                            </button>
                            <a href="{{ route('tasks.index') }}" class="btn btn-secondary px-4 py-2">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create Project -->
<div class="modal fade" id="createProjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-folder-plus me-2"></i>Buat Proyek Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createProjectForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_project_name" class="form-label fw-semibold">
                            Nama Proyek <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="new_project_name" name="name" 
                               placeholder="Contoh: Tugas Mandiri OOP" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_project_description" class="form-label fw-semibold">
                            Deskripsi
                        </label>
                        <textarea class="form-control" id="new_project_description" name="description" 
                                  rows="3" placeholder="Deskripsi proyek..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="new_project_status" class="form-label fw-semibold">
                            Status
                        </label>
                        <select class="form-select" id="new_project_status" name="status">
                            <option value="active" selected>Aktif</option>
                            <option value="on_hold">Ditangguhkan</option>
                            <option value="completed">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnSaveProject">
                        <i class="fas fa-save me-1"></i>Simpan Proyek
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const createProjectForm = document.getElementById('createProjectForm');
    
    if (createProjectForm) {
        createProjectForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = document.getElementById('btnSaveProject');
            const originalText = submitBtn.innerHTML;
            
            // Disable button & show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';
            
            try {
                const response = await fetch('{{ route("projects.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        name: formData.get('name'),
                        description: formData.get('description'),
                        status: formData.get('status'),
                    })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    // Tambah opsi baru ke dropdown
                    const projectSelect = document.getElementById('project_id');
                    const newOption = document.createElement('option');
                    newOption.value = data.project.id;
                    newOption.textContent = data.project.name;
                    newOption.selected = true;
                    projectSelect.appendChild(newOption);
                    
                    // Tutup modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createProjectModal'));
                    modal.hide();
                    
                    // Reset form
                    createProjectForm.reset();
                    
                    // Show success message
                    alert('✅ Proyek "' + data.project.name + '" berhasil dibuat!');
                } else {
                    let errorMsg = 'Gagal membuat proyek';
                    if (data.errors) {
                        Object.values(data.errors).forEach(err => {
                            errorMsg += '\n- ' + err[0];
                        });
                    } else if (data.message) {
                        errorMsg = data.message;
                    }
                    alert('❌ Error: ' + errorMsg);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('❌ Terjadi kesalahan. Silakan coba lagi.');
            } finally {
                // Enable button kembali
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }
});
</script>
@endpush