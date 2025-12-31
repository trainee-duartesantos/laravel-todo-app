<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';

    public function toggle(): self
    {
        return $this === self::PENDING
            ? self::COMPLETED
            : self::PENDING;
    }

    public function isCompleted(): bool
    {
        return $this === self::COMPLETED;
    }
}
