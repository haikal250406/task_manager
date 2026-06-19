@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-users me-2"></i>Daftar User</h5>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah User
        </a>
    </div>
    <div class="card-body">
        <!-- Filter & Search -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama atau email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <!-- Avatar & Name -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->getAvatarUrl() }}" alt="{{ $user->name }}" class="rounded-circle me-3" width="40" height="40">
                                    <div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Role Badge -->
                            <td>
                                <span class="badge {{ $user->getRoleBadgeClass() }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            
                            <!-- Status Badge -->
                            <td>
                                <span class="badge {{ $user->getStatusBadgeClass() }}">
                                    {{ $user->getStatusText() }}
                                </span>
                            </td>
                            
                            <!-- Last Login -->
                            <td>
                                @if($user->last_login_at)
                                    <small>{{ $user->last_login_at->diffForHumans() }}</small>
                                @else
                                    <small class="text-muted">Belum pernah</small>
                                @endif
                            </td>
                            
                            <!-- Actions -->
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Toggle Status -->
                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-{{ $user->is_active ? 'secondary' : 'success' }}" 
                                                title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                onclick="return confirm('Yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} user {{ $user->name }}?')">
                                            <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                        </button>
                                    </form>

                                    <!-- Delete (Protected from self-delete) -->
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                                    onclick="return confirm('Yakin ingin menghapus user {{ $user->name }}? Tindakan ini tidak dapat dibatalkan!')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled title="Tidak bisa hapus diri sendiri">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-users-slash fa-3x mb-3 opacity-25"></i>
                                <p>Tidak ada user ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection