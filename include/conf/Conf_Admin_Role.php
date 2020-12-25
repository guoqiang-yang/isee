<?php

/**
 * 后台配置
 * 权限和岗位是两码事。 但是目前权限是基于岗位的。 岗位粒度为主，然后再根据人的粒度微调。
 */
class Conf_Admin_Role
{
    const ROLE_OP = 5,   //线上运营
        ROLE_FINANCE = 8,   //财务
        ROLE_ADMIN = 99;    //系统管理员(所有权限都有)

    static $_ROLE_MODULE_PAGES = array(
        self::ROLE_FINANCE => array(//财务
            'user' => array('*'),
        ),
    );

    public static function getMODULES($suid, $suser, $modules)
    {
        //管理员
        $admin = Admin_Role_Api::isAdmin($suid, $suser);
        if ($admin)
        {
            return $modules;
        }

        // 获取该角色可以访问的module,page列表
        $roleLevels = Admin_Role_Api::getRoleLevels($suid, $suser);
        $myModulePages = array();
        $allRoles = self::$_ROLE_MODULE_PAGES;
        foreach ($roleLevels as $role => $level)
        {
            $roleConf = $allRoles[$role];
            foreach ($roleConf as $module => $pageTmpArr)
            {
                $myModulePages[$module] = array_merge((array)$myModulePages[$module], $pageTmpArr);
            }
        }

        // 获取module,page的定义
        foreach ($modules as $module => $item)
        {
            if (array_key_exists('extra_uids', $item) && in_array($suid, $item['extra_uids']))
            {
                continue;
            }

            //看不到该模块
            if (!isset($myModulePages[$module]))
            {
                unset($modules[$module]);
                continue;
            }
            //该模块可以看全部页面
            if (in_array('*', $myModulePages[$module]))
            {
                continue;
            }
            //该模块看不到某些页面
            $pages = $item['pages'];
            foreach ($pages as $idx => $pageItem)
            {
                $page = $pageItem['page'];
                if (!in_array($page, $myModulePages[$module]))
                {
                    unset($modules[$module]['pages'][$idx]);
                }
            }
            //重置数组下标
            $modules[$module]['pages'] = array_values($modules[$module]['pages']);
        }

        return $modules;
    }



}
