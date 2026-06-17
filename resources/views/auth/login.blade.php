@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 75vh;">
        <div class="col-md-5">
            
            <div class="card bg-white rounded-4 shadow-lg overflow-hidden" style="border: 1px solid #e2e8f0 !important;">
                <div class="card-body p-5">
                    
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-dark mb-1">Selamat Datang</h3>
                        <p class="text-muted small">Silakan masuk untuk mengelola proyek dan tugas Anda</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-semibold">Alamat Email</label>
                            <input id="email" type="email" class="form-control form-control-lg bg-white rounded-3 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="nama@email.com" style="border: 1px solid #cbd5e1 !important; font-size: 0.95rem;">
                            
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-secondary small fw-semibold">Kata Sandi</label>
                            <input id="password" type="password" class="form-control form-control-lg bg-white rounded-3 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••" style="border: 1px solid #cbd5e1 !important; font-size: 0.95rem;">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} style="border: 1px solid #cbd5e1 !important;">
                                <label class="form-check-label text-muted small" for="remember">
                                    Ingat Saya
                                </label>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-dark btn-lg rounded-3 fw-bold shadow-sm py-2" style="letter-spacing: 0.5px; font-size: 1rem;">
                                Masuk ke Aplikasi
                            </button>
                        </div>

                        @if (Route::has('register'))
                            <div class="text-center mt-4">
                                <p class="text-muted small mb-0">Belum punya akun? 
                                    <a href="{{ route('register') }}" class="text-primary fw-semibold text-decoration-none">Daftar Sekarang</a>
                                </p>
                            </div>
                        @endif

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection