@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row">
        <!-- General Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-cog me-2 text-primary"></i>Pengaturan Umum</h5>
                </div>
                <div class="card-body">
                    @php
                        $generalSettings = $settings['general'] ?? collect();
                    @endphp
                    
                    <div class="mb-3">
                        <label for="app_name" class="form-label fw-semibold">Nama Aplikasi</label>
                        <input type="text" name="settings[app_name]" id="app_name" 
                               class="form-control @error('settings.app_name') is-invalid @enderror" 
                               value="{{ old('settings.app_name', $generalSettings->where('key', 'app_name')->first()->value ?? 'Task Manager') }}">
                        @error('settings.app_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="app_description" class="form-label fw-semibold">Deskripsi Aplikasi</label>
                        <textarea name="settings[app_description]" id="app_description" rows="3" 
                                  class="form-control @error('settings.app_description') is-invalid @enderror">{{ old('settings.app_description', $generalSettings->where('key', 'app_description')->first()->value ?? 'Aplikasi Manajemen Tugas Kolaboratif') }}</textarea>
                        @error('settings.app_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="items_per_page" class="form-label fw-semibold">Item per Halaman</label>
                        <input type="number" name="settings[items_per_page]" id="items_per_page" 
                               class="form-control @error('settings.items_per_page') is-invalid @enderror" 
                               value="{{ old('settings.items_per_page', $generalSettings->where('key', 'items_per_page')->first()->value ?? 15) }}" min="5" max="100">
                        @error('settings.items_per_page')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Jumlah item yang ditampilkan per halaman</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-upload me-2 text-success"></i>Pengaturan Upload</h5>
                </div>
                <div class="card-body">
                    @php
                        $uploadSettings = $settings['upload'] ?? collect();
                    @endphp
                    
                    <div class="mb-3">
                        <label for="max_upload_size" class="form-label fw-semibold">Ukuran Upload Maksimum (KB)</label>
                        <input type="number" name="settings[max_upload_size]" id="max_upload_size" 
                               class="form-control @error('settings.max_upload_size') is-invalid @enderror" 
                               value="{{ old('settings.max_upload_size', $uploadSettings->where('key', 'max_upload_size')->first()->value ?? 2048) }}" min="100">
                        @error('settings.max_upload_size')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Contoh: 2048 KB = 2 MB</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="allowed_extensions" class="form-label fw-semibold">Ekstensi File yang Diizinkan</label>
                        <input type="text" name="settings[allowed_extensions]" id="allowed_extensions" 
                               class="form-control @error('settings.allowed_extensions') is-invalid @enderror" 
                               value="{{ old('settings.allowed_extensions', $uploadSettings->where('key', 'allowed_extensions')->first()->value ?? 'jpg,jpeg,png,pdf,doc,docx') }}">
                        @error('settings.allowed_extensions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Pisahkan dengan koma (,)</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-bell me-2 text-warning"></i>Pengaturan Notifikasi</h5>
                </div>
                <div class="card-body">
                    @php
                        $notificationSettings = $settings['notification'] ?? collect();
                    @endphp
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="settings[enable_email_notification]" 
                                   id="enable_email_notification" value="1"
                                   {{ old('settings.enable_email_notification', $notificationSettings->where('key', 'enable_email_notification')->first()->value ?? 1) == '1' ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="enable_email_notification">
                                Aktifkan Notifikasi Email
                            </label>
                        </div>
                        <small class="text-muted">Kirim notifikasi via email untuk tugas baru dan deadline</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notification_email" class="form-label fw-semibold">Email Pengirim Notifikasi</label>
                        <input type="email" name="settings[notification_email]" id="notification_email" 
                               class="form-control @error('settings.notification_email') is-invalid @enderror" 
                               value="{{ old('settings.notification_email', $notificationSettings->where('key', 'notification_email')->first()->value ?? 'noreply@taskmanager.com') }}">
                        @error('settings.notification_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-shield-alt me-2 text-danger"></i>Pengaturan Keamanan</h5>
                </div>
                <div class="card-body">
                    @php
                        $securitySettings = $settings['security'] ?? collect();
                    @endphp
                    
                    <div class="mb-3">
                        <label for="max_login_attempts" class="form-label fw-semibold">Maksimal Percobaan Login</label>
                        <input type="number" name="settings[max_login_attempts]" id="max_login_attempts" 
                               class="form-control @error('settings.max_login_attempts') is-invalid @enderror" 
                               value="{{ old('settings.max_login_attempts', $securitySettings->where('key', 'max_login_attempts')->first()->value ?? 5) }}" min="1" max="10">
                        @error('settings.max_login_attempts')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Jumlah percobaan login sebelum akun dikunci</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="lockout_duration" class="form-label fw-semibold">Durasi Lockout (Menit)</label>
                        <input type="number" name="settings[lockout_duration]" id="lockout_duration" 
                               class="form-control @error('settings.lockout_duration') is-invalid @enderror" 
                               value="{{ old('settings.lockout_duration', $securitySettings->where('key', 'lockout_duration')->first()->value ?? 15) }}" min="5" max="60">
                        @error('settings.lockout_duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Durasi akun dikunci setelah gagal login</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_min_length" class="form-label fw-semibold">Panjang Minimum Password</label>
                        <input type="number" name="settings[password_min_length]" id="password_min_length" 
                               class="form-control @error('settings.password_min_length') is-invalid @enderror" 
                               value="{{ old('settings.password_min_length', $securitySettings->where('key', 'password_min_length')->first()->value ?? 8) }}" min="6" max="20">
                        @error('settings.password_min_length')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Panjang minimum password untuk user</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-primary px-4 py-2">
                    <i class="fas fa-save me-2"></i>Simpan Pengaturan
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary px-4 py-2">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</form>
@endsection

@section('styles')
<style>
    .form-check-input:checked {
        background-color: #3B82F6;
        border-color: #3B82F6;
    }
</style>
@endsection