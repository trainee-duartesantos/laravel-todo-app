<?php

namespace App\Repositories;

use App\Models\Task;
use App\Enums\TaskStatus;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function all(?TaskStatus $status = null): Collection;

    public function create(array $data): Task;

    public function find(int $id): ?Task;

    public function update(Task $task, array $data): Task;

    public function delete(Task $task): void;

    public function toggle(Task $task): Task;
}
