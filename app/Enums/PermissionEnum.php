<?php

namespace App\Enums;
use \Spatie\Enum\Laravel\Enum;

class PermissionEnum extends Enum
{
    const ADMINS = '管理后台人员';
    const ADMINS_ADD = '管理后台人员添加';

    const ROLES = '管理角色';
    const PERMISSIONS = '管理权限';

    public static $permissionValue = [
        self::ADMINS,
        self::ADMINS_ADD,
        self::ROLES,
        self::PERMISSIONS
    ];
}
