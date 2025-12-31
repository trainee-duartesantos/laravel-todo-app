<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TaskPriority;

class Task extends Model
{
    protected $fillable = [
        'title',
        'status',
        'completed',
        'priority',
    ];

    protected $casts = [
        'status' => TaskStatus::class,
        'priority' => TaskPriority::class,
        'completed' => 'boolean',
    ];

    // Helper opcional (muito útil na view)
    public function isCompleted(): bool
    {
        return $this->status->isCompleted();
    }
}
