<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Collection;

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
