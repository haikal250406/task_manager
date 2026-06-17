@php
    // Logika Pintar: Mengecek apakah deadline sudah lewat hari ini
    // Dan memastikan statusnya BUKAN 'done' (karena kalau sudah selesai, tidak perlu dibilang telat)
    $isOverdue = \Carbon\Carbon::parse($task->deadline)->startOfDay()->isPast() 
                 && strtolower(str_replace([' ', '_', '-'], '', $task->status)) !== 'done';
@endphp

<div class="card shadow-sm rounded-3 mb-3 bg-white custom-task-card {{ $isOverdue ? 'border-start border-danger border-4' : 'border-0' }}">
    <div class="card-body p-3">
        
        <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
            <h6 class="fw-bold text-dark mb-0 flex-grow-1 text-break" style="font-size: 0.95rem;">
                {{ $task->title ?? $task->name }}
            </h6>
            @if(isset($task->type) || isset($task->category))
                <span class="badge {{ strtolower($task->type ?? $task->category) == 'bug' ? 'bg-danger' : 'bg-info text-dark' }} rounded-2" style="font-size: 0.7rem; px-2 py-1">
                    {{ $task->type ?? $task->category }}
                </span>
            @endif
        </div>

        <hr class="my-2 opacity-25">

        <div class="d-flex justify-content-between align-items-center mt-2">
            <span class="small fw-semibold text-capitalize text-muted" style="font-size: 0.8rem;">
                🚨 <span class="{{ strtolower($task->priority) == 'high' ? 'text-danger fw-bold' : 'text-secondary' }}">{{ $task->priority }}</span>
            </span>
            
            <span class="small d-flex align-items-center text-nowrap {{ $isOverdue ? 'text-danger fw-bold' : 'text-muted' }}" style="font-size: 0.75rem;">
                📅 <span class="ms-1">{{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}</span>
                
                @if($isOverdue)
                    <span class="ms-1 badge bg-danger text-white ms-2" style="font-size: 0.65rem;">Terlambat</span>
                @endif
            </span>
        </div>

    </div>
</div>

<style>
    .custom-task-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: pointer;
    }
    .custom-task-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
    }
</style>