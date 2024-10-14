<?php

namespace App\Enum;

enum TaskStatus: string
{
    case TODO = 'todo';
    case ON_PROGRESS = 'on_progress';
    case DONE = 'done';

    public static function fromString(string $status): ?self
    {
        return match ($status) {
            'todo' => self::TODO,
            'on_progress' => self::ON_PROGRESS,
            'done' => self::DONE,
            default => null,
        };
    }
}
