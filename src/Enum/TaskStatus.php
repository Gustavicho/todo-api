<?php

namespace App\Enum;

enum TaskStatus: string
{
  case TODO = 'to-do';
  case ON_PROGRESS = 'on-progress';
  case DONE = 'done';
}
