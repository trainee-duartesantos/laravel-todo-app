<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_can_be_updated_via_json_request(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patchJson("/tasks/{$task->id}", [
                'title' => 'Título atualizado',
                'priority' => 'low',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('task.title', 'Título atualizado');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Título atualizado',
        ]);
    }
}
