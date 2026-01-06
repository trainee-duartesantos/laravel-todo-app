<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Enums\TaskStatus;

class TaskStatusTest extends TestCase
{
    public function test_task_status_toggles_correctly(): void
    {
        $this->assertEquals(
            TaskStatus::COMPLETED,
            TaskStatus::PENDING->toggle()
        );

        $this->assertEquals(
            TaskStatus::PENDING,
            TaskStatus::COMPLETED->toggle()
        );
    }
}
