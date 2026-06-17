@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h4 class="mb-0">Buat Proyek Baru</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Proyek</label>
                <input type="text" name="name" class="form-control" id="name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" id="description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Simpan Proyek</button>
            <a href="{{ route('projects.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection