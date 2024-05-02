<?php

namespace App\Enums;

enum Role: int
{
    /**
     * Role User
     */
    public const USER = 1;

    /**
     * Role Admin
     */
    public const ADMIN = 2;

    /**
     * Role Store
     */
    public const STORE = 3;

    /**
     * Role Staff.
     */
    public const STAFF = 4;
}
