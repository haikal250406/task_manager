<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'status',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'active',
    ];

    // === RELATIONSHIPS ===
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class);
    }

    // === SCOPES ===
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // === HELPER METHODS ===
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'active' => 'bg-success',
            'completed' => 'bg-primary',
            'on_hold' => 'bg-warning',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function getStatusText(): string
    {
        return match($this->status) {
            'active' => 'Aktif',
            'completed' => 'Selesai',
            'on_hold' => 'Ditangguhkan',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getProgressPercentage(): int
    {
        $totalTasks = $this->tasks()->count();
        if ($totalTasks === 0) return 0;
        
        $completedTasks = $this->tasks()
            ->whereRaw('LOWER(status) IN (?, ?, ?)', ['done', 'completed', 'selesai'])
            ->count();
        
        return round(($completedTasks / $totalTasks) * 100);
    }

    public function getTaskStats(): array
    {
        $total = $this->tasks()->count();
        $completed = $this->tasks()
            ->whereRaw('LOWER(status) IN (?, ?, ?)', ['done', 'completed', 'selesai'])
            ->count();
        $inProgress = $this->tasks()
            ->whereRaw('LOWER(status) = ?', ['in_progress'])
            ->count();
        $todo = $this->tasks()
            ->whereRaw('LOWER(status) = ?', ['to_do'])
            ->count();
        
        return compact('total', 'completed', 'inProgress', 'todo');
    }
}