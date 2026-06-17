@extends('layouts.app')

@section('content')
@php
    // Logika penyaring pintar: Mengubah ke huruf kecil dan menghapus spasi/strip/underscore
    $todoTasks = $tasks->filter(function($item) {
        $status = strtolower(str_replace([' ', '_', '-'], '', $item->status));
        return $status === 'todo';
    });

    $inProgressTasks = $tasks->filter(function($item) {
        $status = strtolower(str_replace([' ', '_', '-'], '', $item->status));
        return $status === 'inprogress';
    });

    $doneTasks = $tasks->filter(function($item) {
        $status = strtolower(str_replace([' ', '_', '-'], '', $item->status));
        return $status === 'done';
    });
@endphp

<div class="container py-2">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom g-3">
        <div>
            <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-secondary rounded-3 mb-2">
                &larr; Kembali ke Daftar Proyek
            </a>
            <h1 class="fw-bold text-dark mb-1">{{ $project->name }}</h1>
            <p class="text-muted mb-0">{{ $project->description ?? 'Tidak ada deskripsi proyek.' }}</p>
        </div>
        <div>
            <a href="{{ route('tasks.create', ['project' => $project->id]) }}" class="btn btn-primary rounded-3 fw-semibold shadow-sm px-4 py-2">
    + Tambah Tugas Baru
</a>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-md-4">
            <div class="card bg-light border-0 rounded-4 p-3 shadow-sm" style="border-top: 4px solid #6c757d !important;">
                <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                    <h5 class="fw-bold text-secondary mb-0">To Do</h5>
                    <span class="badge bg-secondary rounded-pill">{{ $todoTasks->count() }}</span>
                </div>
                
                <div class="kanban-lane" style="min-height: 400px;">
                    @forelse($todoTasks as $task)
                        @include('projects.partials.task-card', ['task' => $task])
                    @empty
                        <div class="text-center py-4 text-muted small border border-dashed rounded-3 bg-white opacity-75">
                            Belum ada tugas.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-light border-0 rounded-4 p-3 shadow-sm" style="border-top: 4px solid #0d6efd !important;">
                <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                    <h5 class="fw-bold text-primary mb-0">In Progress</h5>
                    <span class="badge bg-primary rounded-pill">{{ $inProgressTasks->count() }}</span>
                </div>
                
                <div class="kanban-lane" style="min-height: 400px;">
                    @forelse($inProgressTasks as $task)
                        @include('projects.partials.task-card', ['task' => $task])
                    @empty
                        <div class="text-center py-4 text-muted small border border-dashed rounded-3 bg-white opacity-75">
                            Belum ada tugas berjalan.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-light border-0 rounded-4 p-3 shadow-sm" style="border-top: 4px solid #198754 !important;">
                <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                    <h5 class="fw-bold text-success mb-0">Done</h5>
                    <span class="badge bg-success rounded-pill">{{ $doneTasks->count() }}</span>
                </div>
                
                <div class="kanban-lane" style="min-height: 400px;">
                    @forelse($doneTasks as $task)
                        @include('projects.partials.task-card', ['task' => $task])
                    @empty
                        <div class="text-center py-4 text-muted small border border-dashed rounded-3 bg-white opacity-75">
                            Belum ada tugas selesai.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .border-dashed {
        border-style: dashed !important;
        border-width: 2px !important;
    }
</style>
@endsection