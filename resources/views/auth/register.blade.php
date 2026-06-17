@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-5">
            
            <div class="card bg-white rounded-4 shadow-lg overflow-hidden" style="border: 1px solid #e2e8f0 !important;">
                <div class="card-body p-5">
                    
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-dark mb-1">Daftar Akun</h3>
                        <p class="text-muted small">Mulai mengelola proyek dan tugas Anda dengan mudah</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-semibold">Nama Lengkap</label>
                            <input id="name" type="text" class="form-control form-control-lg bg-white rounded-3 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nama Anda" style="border: 1px solid #cbd5e1 !important; font-size: 0.95rem;">
                            
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-semibold">Alamat Email</label>
                            <input id="email" type="email" class="form-control form-control-lg bg-white rounded-3 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="nama@email.com" style="border: 1px solid #cbd5e1 !important; font-size: 0.95rem;">
                            
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-semibold">Kata Sandi</label>
                            <input id="password" type="password" class="form-control form-control-lg bg-white rounded-3 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="••••••••" style="border: 1px solid #cbd5e1 !important; font-size: 0.95rem;">
                            
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-secondary small fw-semibold">Konfirmasi Kata Sandi</label>
                            <input id="password-confirm" type="password" class="form-control form-control-lg bg-white rounded-3" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" style="border: 1px solid #cbd5e1 !important; font-size: 0.95rem;">
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-dark btn-lg rounded-3 fw-bold shadow-sm py-2" style="letter-spacing: 0.5px; font-size: 1rem;">
                                Buat Akun Baru
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <p class="text-muted small mb-0">Sudah punya akun? 
                                <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">Masuk Di Sini</a>
                            </p>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection