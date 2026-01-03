<?php

namespace App\Repositories;

use App\Models\Task;
use App\Enums\TaskStatus;
use App\Enums\TaskPriority;
use App\Enums\TaskDueStatus;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function all(
        ?TaskStatus $status,
        ?TaskPriority $priority,
        ?TaskDueStatus $due
    ): Collection;

    public function create(array $data);

    public function find(int $id): ?Task;

    public function update(Task $task, array $data): Task;

    public function delete(Task $task): void;

    public function toggle(Task $task): Task;
}
