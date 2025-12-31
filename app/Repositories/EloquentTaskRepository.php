<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Collection;
use App\Enums\TaskStatus;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function all(?TaskStatus $status = null): Collection
    {
        return Task::query()
            ->when($status, fn ($q) =>
                $q->where('status', $status->value)
            )
            ->orderByDesc('created_at')
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
        $task->update($data);

        return $task;
    }
}
