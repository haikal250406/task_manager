<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role', 
        'is_active', 
        'last_login_at',
        'phone',
        'avatar',
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'role' => 'string',
    ];

    protected $attributes = [
        'role' => 'user',
        'is_active' => true,
    ];

    // === ENCAPSULATION: Role Management ===
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    // === POLYMORPHISM ===
    public function getDashboardRoute(): string
    {
        return match($this->role) {
            'admin' => route('admin.dashboard'),
            'user' => route('dashboard'),
            default => route('login'),
        };
    }

    // === HELPER METHODS ===
    public function getRoleBadgeClass(): string
    {
        return match($this->role) {
            'admin' => 'bg-danger',
            'user' => 'bg-primary',
            default => 'bg-secondary',
        };
    }

    public function getRoleText(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'user' => 'User',
            default => ucfirst($this->role),
        };
    }

    public function getStatusBadgeClass(): string
    {
        return $this->is_active ? 'bg-success' : 'bg-secondary';
    }

    public function getStatusText(): string
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }

    public function getAvatarUrl(): string
    {
        if (isset($this->avatar) && $this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    // === RELATIONSHIPS ===
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // === SCOPES ===
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // === ACCESSORS ===
    public function getFullNameAttribute()
    {
        return ucfirst($this->name);
    }
}