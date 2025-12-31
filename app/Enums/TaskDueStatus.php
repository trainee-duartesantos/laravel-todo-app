<?php

namespace App\Enums;

use Carbon\Carbon;

enum TaskDueStatus: string
{
    case OVERDUE = 'overdue';
    case TODAY = 'today';
    case FUTURE = 'future';
    case NONE = 'none';

    public static function fromDate(?Carbon $date): self
    {
        if (!$date) {
            return self::NONE;
        }

        if ($date->isPast() && !$date->isToday()) {
            return self::OVERDUE;
        }

        if ($date->isToday()) {
            return self::TODAY;
        }

        return self::FUTURE;
    }

    public function color(): string
    {
        return match ($this) {
            self::OVERDUE => 'bg-red-200 text-red-800',
            self::TODAY => 'bg-yellow-200 text-yellow-800',
            self::FUTURE => 'bg-green-200 text-green-800',
            self::NONE => 'hidden',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::OVERDUE => 'Atrasada',
            self::TODAY => 'Hoje',
            self::FUTURE => 'Futura',
            self::NONE => '',
        };
    }
}
