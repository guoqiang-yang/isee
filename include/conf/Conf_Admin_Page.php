<?php
/**
 * 页面列表
 */
class Conf_Admin_Page
{
    private static $MODULES = [
        'dashboard' => [
            'name' => '总览',
            'url' => '/dashboard.php',
        ],
        'test1' => [
            'name' => '测试一',
            'sub_pages' => [
                ['name' => '测试1.1', 'url' => '/test/test11.php'],
                ['name' => '测试1.2', 'url' => '/test/test12.php'],
            ],
        ],
        'test2' => [
            'name' => '测试二',
            'sub_pages' => [
                ['name' => '测试2', 'url' => '/test/test2.php'],
            ],
        ],
    ];

    public static function getMODULES($suid, $suser)
    {
        return self::$MODULES;
    }

    public static function getFirstPage($suid, $suser)
    {
        $url = '';
        $roleLevels = Admin_Role_Api::getRoleLevels($suid, $suser);
        if (isset($roleLevels[Conf_Admin_Role::ROLE_ADMIN]))
        {
            $url = '/purchasing/recycler_list.php';
            //$url = '/dc/receipt_list.php';
        }

        return $url;
    }
}
