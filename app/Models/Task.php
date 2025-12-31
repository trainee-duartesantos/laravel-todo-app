<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TaskPriority;
use App\Enums\TaskDueStatus;

class Task extends Model
{
    protected $fillable = [
        'title',
        'status',
        'completed',
        'priority',
        'due_date',
    ];

    protected $casts = [
        'status' => TaskStatus::class,
        'priority' => TaskPriority::class,
        'completed' => 'boolean',
        'due_date' => 'date',
    ];

    // Helper opcional (muito útil na view)
    public function isCompleted(): bool
    {
        return $this->status->isCompleted();
    }

    public function dueStatus(): TaskDueStatus
    {
        return TaskDueStatus::fromDate($this->due_date);
    }
}
