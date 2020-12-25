<?php

/**
 * 角色相关【未使用】
 */
class Admin_Role_Api extends Base_Api
{
    public static function getStaffOfRole($role)
    {
        $as = new Admin_Staff();
        $list = $as->getByRole($role);
        return $list;
    }

    public static function hasRole($suser, $role)
    {
        if (empty($suser)) return false;

        $roles = array_unique(explode(',',$suser['role']));
        return in_array($role, $roles);
    }

    public static function isAdmin($suid, $suser=array())
    {
        if (empty($suser))
        {
            if (empty($suid))
            {
                return false;
            }
            $as = new Admin_Staff();
            $suser = $as->get($suid);
        }
        return Admin_Role_Api::hasRole($suser, Conf_Admin_Role::ROLE_ADMIN);
    }

    public static function hasAuthority($func, $suid, $suser = array())
    {
        $funcAuthArr = Conf_Admin_Role::getFuncAuth();
        assert(isset($funcAuthArr[$func]));

        // 是否是特殊指定的操作者
        if (in_array($suid, (array)$funcAuthArr[$func]['uids']))
        {
            return true;
        }

        // 是否是系统管理员
        if (empty($suser))
        {
            if (empty($suid))
            {
                return false;
            }
            $as = new Admin_Staff();
            $suser = $as->get($suid);
        }
        $admin = Admin_Role_Api::isAdmin($suid, $suser);
        if ($admin)
        {
            return true;
        }

        // 是否有角色
        $role = $suser['role'];
        $ret = in_array($role, (array)$funcAuthArr[$func]['roles']);
        return $ret;
    }

    public static function getRoleLevels($suid, $suser = array())
    {
        if (empty($suser))
        {
            if (empty($suid))
            {
                return false;
            }
            $as = new Admin_Staff();
            $suser = $as->get($suid);
        }

        $roleStr = explode(',', $suser['role']);
        $rolesLevels = array();
        foreach ($roleStr as $roleItem)
        {
            list($role, $level) = explode(':', $roleItem);
            $level = intval($level);
            $rolesLevels [$role]= $level;
        }

        return $rolesLevels;
    }
}
