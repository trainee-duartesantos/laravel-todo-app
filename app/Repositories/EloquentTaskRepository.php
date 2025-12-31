<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Collection;
use App\Enums\TaskStatus;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function all(): Collection
    {
        return Task::latest()->get();
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function find(int $id): ?Task
    {
        return Task::find($id);
    }

    public function toggle(int $id): void
    {
        $task = Task::findOrFail($id);

        $task->update([
            'completed' => ! $task->completed,
            'status' => $task->completed
                ? TaskStatus::PENDING
                : TaskStatus::COMPLETED,
        ]);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);

        return $task;
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }
}
