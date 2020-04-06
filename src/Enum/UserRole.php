<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static UserRole USER()
 * @method static UserRole ADMIN()
 */
class UserRole extends Enum
{
    private const USER = 'USER';
    private const ADMIN = 'ADMIN';
}
