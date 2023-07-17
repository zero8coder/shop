<?php

namespace App\Enums;
use \Spatie\Enum\Laravel\Enum;

class PermissionEnum extends Enum
{
    const ADMINS = '管理后台人员';
    const ADMINS_VIEW_ANY = '管理后台人员查看所有';
    const ADMINS_CREATE = '管理后台人员添加';
    const ADMINS_UPDATE = '管理员后台人员修改';
    const ADMINS_DELETE = '管理员后台人员删除';

    const ROLES = '管理角色';
    const PERMISSIONS = '管理权限';

    public static $permissionValue = [
        self::ADMINS,
        self::ADMINS_VIEW_ANY,
        self::ADMINS_CREATE,
        self::ADMINS_UPDATE,
        self::ADMINS_DELETE,
        self::ROLES,
        self::PERMISSIONS
    ];
}
