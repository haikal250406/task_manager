@extends('layouts.admin')

@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Aktivitas Sistem</h5>
            <small class="text-muted">Total: <strong>{{ $logs->total() }}</strong> aktivitas tercatat</small>
        </div>
        <div class="d-flex gap-2">
            <!-- Tombol Clear Old Logs -->
            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#clearOldModal">
                <i class="fas fa-broom me-1"></i> Bersihkan Log Lama
            </button>
            <!-- Tombol Refresh -->
            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-sync me-1"></i> Refresh
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Cari</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari aktivitas..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">User</label>
                    <select name="user_id" class="form-select">
                        <option value="">Semua User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Aksi</label>
                    <select name="action" class="form-select">
                        <option value="">Semua Aksi</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ ucfirst($action) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Dari Tanggal</label>
                    <input type="date" name="date_from" class="form-control" 
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Sampai Tanggal</label>
                    <input type="date" name="date_to" class="form-control" 
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-md-1 d-flex align-items-end gap-1">
                    <button type="submit" class="btn btn-primary" title="Filter">
                        <i class="fas fa-filter"></i>
                    </button>
                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary" title="Reset Filter">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
            
            <!-- Active Filters Info -->
            @if(request()->hasAny(['search', 'user_id', 'action', 'date_from', 'date_to']))
                <div class="mt-3 p-2 bg-light rounded">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>Filter aktif:
                        @if(request('search'))
                            <span class="badge bg-primary ms-1">Search: {{ request('search') }}</span>
                        @endif
                        @if(request('user_id'))
                            <span class="badge bg-info ms-1">User: {{ $users->find(request('user_id'))->name ?? 'Unknown' }}</span>
                        @endif
                        @if(request('action'))
                            <span class="badge bg-success ms-1">Action: {{ ucfirst(request('action')) }}</span>
                        @endif
                        @if(request('date_from'))
                            <span class="badge bg-warning ms-1">From: {{ request('date_from') }}</span>
                        @endif
                        @if(request('date_to'))
                            <span class="badge bg-warning ms-1">To: {{ request('date_to') }}</span>
                        @endif
                        <a href="{{ route('admin.activity-logs.index') }}" class="text-danger ms-2">
                            <i class="fas fa-times-circle"></i> Hapus semua filter
                        </a>
                    </small>
                </div>
            @endif
        </form>

        <!-- Activity Logs Table -->
        @if($logs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="15%">Waktu</th>
                            <th width="15%">User</th>
                            <th width="10%">Aksi</th>
                            <th width="35%">Deskripsi</th>
                            <th width="10%">IP Address</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>
                                    <div class="fw-semibold small">{{ $log->created_at->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                                    <br>
                                    <small class="text-muted fst-italic">{{ $log->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    @if($log->user)
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $log->user->getAvatarUrl() }}" 
                                                 alt="{{ $log->user->name }}" 
                                                 class="rounded-circle me-2" width="32" height="32">
                                            <div>
                                                <div class="fw-semibold small">{{ $log->user->name }}</div>
                                                <small class="text-muted">{{ $log->user->email }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary bg-opacity-10 p-2 rounded-circle me-2">
                                                <i class="fas fa-robot text-secondary"></i>
                                            </div>
                                            <small class="text-muted">System</small>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $log->getActionBadgeClass() }} px-2 py-1">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ Str::limit($log->description, 80) }}</small>
                                </td>
                                <td>
                                    <small class="text-muted font-monospace">{{ $log->ip_address }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.activity-logs.show', $log) }}" 
                                           class="btn btn-outline-primary" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.activity-logs.destroy', $log) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus Log"
                                                    onclick="return confirm('Yakin ingin menghapus log ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination & Info -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small">
                    Menampilkan {{ $logs->firstItem() }} - {{ $logs->lastItem() }} dari {{ $logs->total() }} aktivitas
                </div>
                @if($logs->hasPages())
                    <div>
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <i class="fas fa-history fa-4x text-muted opacity-25 mb-3"></i>
                <h5 class="text-muted">Tidak ada aktivitas ditemukan</h5>
                <p class="text-muted mb-3">
                    @if(request()->hasAny(['search', 'user_id', 'action', 'date_from', 'date_to']))
                        Coba ubah filter pencarian Anda
                    @else
                        Semua aktivitas sistem akan tercatat di sini
                    @endif
                </p>
                @if(request()->hasAny(['search', 'user_id', 'action', 'date_from', 'date_to']))
                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-primary">
                        <i class="fas fa-times me-1"></i> Reset Filter
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Modal: Clear Old Logs -->
<div class="modal fade" id="clearOldModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-dark">
                    <i class="fas fa-broom me-2"></i>Bersihkan Log Lama
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.activity-logs.clear-old') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="mb-3">Hapus semua activity logs yang lebih lama dari:</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Hari</label>
                        <select name="days" class="form-select">
                            <option value="7">7 hari yang lalu</option>
                            <option value="14">14 hari yang lalu</option>
                            <option value="30" selected>30 hari yang lalu</option>
                            <option value="60">60 hari yang lalu</option>
                            <option value="90">90 hari yang lalu</option>
                        </select>
                    </div>
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-warning" 
                            onclick="return confirm('Apakah Anda yakin ingin menghapus log lama?')">
                        <i class="fas fa-broom me-1"></i>Bersihkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.05);
    }
    .font-monospace {
        font-family: 'Courier New', monospace;
        font-size: 0.85rem;
    }
</style>
@endsection