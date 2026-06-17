<?php

namespace App\Models;

// Perhatikan: Class ini 'extends' dari Task, BUKAN Model (Ini adalah INHERITANCE)
class BugTask extends Task 
{
    // Setiap kali BugTask dibuat, otomatis diset type-nya menjadi 'Bug'
    protected static function booted()
    {
        static::creating(function ($task) {
            $task->type = 'Bug';
        });

        // Global scope: Jika memanggil BugTask::all(), hanya mengambil data bertipe 'Bug'
        static::addGlobalScope('bug', function ($builder) {
            $builder->where('type', 'Bug');
        });
    }
}
