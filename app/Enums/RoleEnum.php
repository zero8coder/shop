<?php

namespace App\Enums;
use \Spatie\Enum\Laravel\Enum;

class RoleEnum extends Enum
{
    const SUPER_ADMIN = '超级管理员';

    public static $roleValue = [
        self::SUPER_ADMIN,
    ];
}
