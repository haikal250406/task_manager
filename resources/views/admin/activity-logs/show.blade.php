@extends('layouts.admin')

@section('title', 'Detail Activity Log')
@section('page-title', 'Detail Activity Log')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Detail Aktivitas</h5>
        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
    <div class="card-body p-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="fw-bold text-dark mb-3">Informasi Umum</h6>
                <table class="table table-borderless">
                    <tr>
                        <td width="30%"><strong>Waktu:</strong></td>
                        <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Relatif:</strong></td>
                        <td>{{ $log->created_at->diffForHumans() }}</td>
                    </tr>
                    <tr>
                        <td><strong>User:</strong></td>
                        <td>
                            @if($log->user)
                                {{ $log->user->name }} ({{ $log->user->email }})
                            @else
                                <span class="text-muted">System</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Aksi:</strong></td>
                        <td>
                            <span class="badge {{ $log->getActionBadgeClass() }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold text-dark mb-3">Informasi Teknis</h6>
                <table class="table table-borderless">
                    <tr>
                        <td width="30%"><strong>IP Address:</strong></td>
                        <td><code>{{ $log->ip_address }}</code></td>
                    </tr>
                    <tr>
                        <td><strong>User Agent:</strong></td>
                        <td><small class="text-muted">{{ Str::limit($log->user_agent, 100) }}</small></td>
                    </tr>
                    <tr>
                        <td><strong>Model:</strong></td>
                        <td><code>{{ $log->model_type }}</code></td>
                    </tr>
                    <tr>
                        <td><strong>Model ID:</strong></td>
                        <td><code>{{ $log->model_id }}</code></td>
                    </tr>
                </table>
            </div>
        </div>

        <hr>

        <div class="mb-4">
            <h6 class="fw-bold text-dark mb-3">Deskripsi</h6>
            <div class="alert alert-light border">
                {{ $log->description }}
            </div>
        </div>

        @if($log->old_values)
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="fas fa-arrow-left me-1 text-danger"></i>Data Lama (Old Values)
                </h6>
                <div class="card bg-light">
                    <div class="card-body">
                        <pre class="mb-0"><code>{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                </div>
            </div>
        @endif

        @if($log->new_values)
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="fas fa-arrow-right me-1 text-success"></i>Data Baru (New Values)
                </h6>
                <div class="card bg-light">
                    <div class="card-body">
                        <pre class="mb-0"><code>{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection