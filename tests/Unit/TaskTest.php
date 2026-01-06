<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Enums\TaskStatus;
use App\Enums\TaskPriority;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_can_be_created(): void
    {
        $task = Task::create([
            'title' => 'Tarefa de teste',
            'priority' => TaskPriority::MEDIUM,
            'status' => TaskStatus::PENDING,
            'completed' => false,
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Tarefa de teste',
        ]);
    }
}
