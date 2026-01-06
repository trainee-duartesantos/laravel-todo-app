<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TaskPriority;
use App\Enums\TaskDueStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'status',
        'completed',
        'priority',
        'due_date',
        'description',
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
        if ($this->isCompleted()) {
            return TaskDueStatus::NONE;
        }

        return TaskDueStatus::fromDate($this->due_date);
    }
}
