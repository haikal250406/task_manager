<?php

namespace App\Models;

// INHERITANCE
class FeatureTask extends Task 
{
    protected static function booted()
    {
        static::creating(function ($task) {
            $task->type = 'Feature';
        });

        static::addGlobalScope('feature', function ($builder) {
            $builder->where('type', 'Feature');
        });
    }
}
