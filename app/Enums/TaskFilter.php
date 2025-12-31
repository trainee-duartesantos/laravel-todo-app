<?php

namespace App\Enums;

enum TaskFilter: string
{
    case ALL = 'all';
    case PENDING = 'pending';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::ALL => 'Todas',
            self::PENDING => 'Pendentes',
            self::COMPLETED => 'Concluídas',
        };
    }
}
