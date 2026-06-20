<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'deadline',
        'project_id',
        'user_id',
    ];

    protected $casts = [
        'deadline' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'to_do',
        'priority' => 'medium',
    ];

    // === RELATIONSHIPS ===
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    // === SCOPES ===
    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['done', 'completed']);
    }

    public function scopeOverdue($query)
    {
        return $query->where('deadline', '<', now())
            ->whereNotIn('status', ['done', 'completed']);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // === HELPER METHODS ===
    public function getStatusBadgeClass(): string
    {
        return match(strtolower($this->status)) {
            'done', 'completed' => 'bg-success',
            'in_progress' => 'bg-warning',
            'to_do' => 'bg-secondary',
            default => 'bg-secondary',
        };
    }

    public function getStatusText(): string
    {
        return match(strtolower($this->status)) {
            'done', 'completed' => 'Selesai',
            'in_progress' => 'Dalam Progress',
            'to_do' => 'To Do',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function getPriorityBadgeClass(): string
    {
        return match(strtolower($this->priority)) {
            'critical' => 'bg-danger',
            'high' => 'bg-warning',
            'medium' => 'bg-info',
            'low' => 'bg-success',
            default => 'bg-secondary',
        };
    }

    public function getPriorityText(): string
    {
        return match(strtolower($this->priority)) {
            'critical' => 'Kritis',
            'high' => 'Tinggi',
            'medium' => 'Sedang',
            'low' => 'Rendah',
            default => ucfirst($this->priority),
        };
    }

    public function isOverdue(): bool
    {
        return $this->deadline && 
               $this->deadline->isPast() && 
               !in_array(strtolower($this->status), ['done', 'completed']);
    }

    public function getDaysUntilDeadline(): ?int
    {
        if (!$this->deadline) return null;
        
        return now()->diffInDays($this->deadline, false);
    }

    public function getDeadlineStatus(): string
    {
        if (!$this->deadline) return 'no_deadline';
        
        if ($this->isOverdue()) return 'overdue';
        
        $days = $this->getDaysUntilDeadline();
        
        if ($days <= 3) return 'urgent';
        if ($days <= 7) return 'soon';
        
        return 'on_track';
    }
}