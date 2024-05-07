<?php

namespace App\Enums;

enum TaskStatus: int
{
    /**
     * task doing
     */
    public const DOING = 1;

    /**
     * task completed
     */
    public const COMPLETED = 2;

    /**
     * task expired
     */
    public const EXPIRED = 3;
}
