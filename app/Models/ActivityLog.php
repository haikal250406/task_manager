<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'model_type', 'model_id', 
        'description', 'old_values', 'new_values', 
        'ip_address', 'user_agent'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // === RELATIONSHIPS ===
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // === POLYMORPHISM: Get related model ===
    public function model()
    {
        return $this->morphTo('model', 'model_type', 'model_id');
    }

    // === HELPER METHODS ===
    public function getActionBadgeClass(): string
    {
        return match($this->action) {
            'create' => 'bg-success',
            'update' => 'bg-warning',
            'delete' => 'bg-danger',
            'login' => 'bg-info',
            'logout' => 'bg-secondary',
            default => 'bg-primary',
        };
    }
}