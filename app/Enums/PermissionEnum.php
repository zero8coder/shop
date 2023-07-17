<?php

namespace App\Enums;
use \Spatie\Enum\Laravel\Enum;

class PermissionEnum extends Enum
{
    const ADMINS = '管理后台人员';
    const ADMINS_VIEW_ANY = '查看所有后台人员';
    const ADMINS_CREATE = '添加后台人员';
    const ADMINS_UPDATE = '修改后台人员';
    const ADMINS_DELETE = '删除后台人员';
    const ADMINS_EXPORT = '导出后台人员';

    const ROLES = '管理角色';
    const ROLES_VIEW_ANY = '查看所有角色';

    const PERMISSIONS = '管理权限';

    public static $permissionValue = [
        self::ADMINS,
        self::ADMINS_VIEW_ANY,
        self::ADMINS_CREATE,
        self::ADMINS_UPDATE,
        self::ADMINS_DELETE,
        self::ADMINS_EXPORT,

        self::ROLES,
        self::ROLES_VIEW_ANY,

        self::PERMISSIONS
    ];
}
