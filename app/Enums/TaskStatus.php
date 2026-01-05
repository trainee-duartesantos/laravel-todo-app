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

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendente',
            self::COMPLETED => 'Concluída',
        };
    }
}
