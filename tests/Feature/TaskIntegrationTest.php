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
        // 1️⃣ Criar tarefa via HTTP (como um utilizador real)
        $response = $this->post('/tasks', [
            'title' => 'Teste de integração',
            'priority' => TaskPriority::MEDIUM->value,
            'due_date' => null,
        ]);

        // 2️⃣ Verificar redirect (UX correta)
        $response->assertRedirect();

        // 3️⃣ Confirmar que a tarefa existe na base de dados
        $this->assertDatabaseHas('tasks', [
            'title' => 'Teste de integração',
            'priority' => TaskPriority::MEDIUM->value,
            'status' => TaskStatus::PENDING->value,
        ]);

        // 4️⃣ Confirmar que aparece na listagem
        $listResponse = $this->get('/tasks');
        $listResponse->assertStatus(200);
        $listResponse->assertSee('Teste de integração');
    }
}
