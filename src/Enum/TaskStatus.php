<?php

namespace App\Enum;

enum TaskStatus: string
{
    case TODO = 'to_do';
    case ON_PROGRESS = 'on_progress';
    case DONE = 'done';
}
