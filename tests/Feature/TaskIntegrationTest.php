<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;

class TaskIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_task_and_displays_it_in_the_list()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/tasks', [
                'title' => 'Teste de integração',
                'priority' => TaskPriority::MEDIUM->value,
                'due_date' => null,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('tasks', [
            'title' => 'Teste de integração',
            'priority' => TaskPriority::MEDIUM->value,
            'status' => TaskStatus::PENDING->value,
        ]);

        $listResponse = $this->actingAs($user)->get('/tasks');
        $listResponse->assertOk();
        $listResponse->assertSee('Teste de integração');
    }

}
