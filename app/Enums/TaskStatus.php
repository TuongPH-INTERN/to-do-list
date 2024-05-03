<?php

namespace App\Enums;

enum TaskStatus: int
{
    public const DOING = 1;

    public const COMPLETED = 2;

    public const EXPIRED = 3;
}
