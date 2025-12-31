<?php

namespace App\Enums;

enum TaskPriority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';

    public function label(): string
    {
        return match ($this) {
            self::LOW => 'Baixa',
            self::MEDIUM => 'Média',
            self::HIGH => 'Alta',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::LOW => 'bg-green-100 text-green-700',
            self::MEDIUM => 'bg-yellow-100 text-yellow-700',
            self::HIGH => 'bg-red-100 text-red-700',
        };
    }
}
