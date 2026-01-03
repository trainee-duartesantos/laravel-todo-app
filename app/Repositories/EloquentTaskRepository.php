<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Collection;
use App\Enums\TaskStatus;
use App\Enums\TaskPriority;
use App\Enums\TaskDueStatus;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function all(
            ?TaskStatus $status,
            ?TaskPriority $priority,
            ?TaskDueStatus $due
        ): Collection
    {
        $query = Task::query();

        // Estado
        if ($status) {
            $query->where('status', $status->value);
        }

        // Prioridade
        if ($priority) {
            $query->where('priority', $priority->value);
        }

        // Vencimento
        if ($due) {
            $today = now()->toDateString();

            match ($due) {
                TaskDueStatus::OVERDUE =>
                    $query->whereNotNull('due_date')
                          ->whereDate('due_date', '<', $today),

                TaskDueStatus::TODAY =>
                    $query->whereDate('due_date', $today),

                TaskDueStatus::FUTURE =>
                    $query->whereDate('due_date', '>', $today),

                TaskDueStatus::NONE =>
                    $query->whereNull('due_date'),
            };
        }

        return $query
            ->orderBy('completed')
            ->orderByRaw('due_date IS NULL')
            ->orderBy('due_date')
            ->get();
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function find(int $id): ?Task
    {
        return Task::find($id);
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    public function toggle(Task $task): Task
    {
        $task->status = $task->status->toggle();
        $task->completed = $task->status->isCompleted();
        $task->save();

        return $task;
    }

    public function update(Task $task, array $data): Task
    {
        $task->fill([
            'title' => $data['title'],
            'priority' => $data['priority'],
            'due_date' => $data['due_date'] ?? null,
        ]);

        $task->save();

        return $task;
    }
}
