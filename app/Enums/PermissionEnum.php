<?php

namespace App\Enums;
use \Spatie\Enum\Laravel\Enum;

class PermissionEnum extends Enum
{
    const ADMINS_MANAGE = '管理后台人员';
    const ADMINS_VIEW_ANY = '查看所有后台人员';
    const ADMINS_CREATE = '添加后台人员';
    const ADMINS_UPDATE = '修改后台人员';
    const ADMINS_DELETE = '删除后台人员';
    const ADMINS_EXPORT = '导出后台人员';

    const ROLES_MANAGE = '管理角色';
    const ROLES_VIEW_ANY = '查看所有角色';
    const ROLES_CREATE = '添加角色';
    const ROLES_UPDATE = '修改角色';
    const ROLES_DELETE = '删除角色';
    const ROLES_EXPORT = '导出角色';

    const PERMISSIONS_MANAGE = '管理权限';
    const PERMISSIONS_VIEW_ANY = '查看所有权限';
    const PERMISSIONS_CREATE = '添加权限';
    const PERMISSIONS_UPDATE = '修改权限';
    const PERMISSIONS_DELETE = '删除权限';
    const PERMISSIONS_EXPORT = '导出权限';

    const MENU_MANAGE = '管理菜单';
    const MENU_VIEW_ANY = '查看所有菜单';
    const MENU_CREATE = '添加菜单';


    public static $permissionValue = [
        self::ADMINS_MANAGE,
        self::ADMINS_VIEW_ANY,
        self::ADMINS_CREATE,
        self::ADMINS_UPDATE,
        self::ADMINS_DELETE,
        self::ADMINS_EXPORT,

        self::ROLES_MANAGE,
        self::ROLES_VIEW_ANY,
        self::ROLES_CREATE,
        self::ROLES_UPDATE,
        self::ROLES_DELETE,
        self::ROLES_EXPORT,

        self::PERMISSIONS_MANAGE,
        self::PERMISSIONS_VIEW_ANY,
        self::PERMISSIONS_CREATE,
        self::PERMISSIONS_UPDATE,
        self::PERMISSIONS_DELETE,
        self::PERMISSIONS_EXPORT,

        self::MENU_MANAGE,
        self::MENU_VIEW_ANY,
        self::MENU_CREATE
    ];
}
