<?php
/**
 * 页面列表
 */
class Conf_Admin_Page
{
    /**
     * class_name: fi-air-play, fi-briefcase, fi-box, fi-bar-graph-2, fi-disc, fi-layout,
     *              fi-share, fi-paper-stack, fi-clock, fi-map
     */
    private static $MODULES = array(
        'material' => array(
            'name' => '食材',
            'class_name' => 'mdi mdi-corn',
            'url' => '/material/material_list.php',
        ),
        'recipe' => array(
            'name' => '烹饪',
            'class_name' => 'mdi mdi-food-variant',
            'sub_pages' => array(
                array('name' => '菜谱列表', 'url' => '/recipe/recipe_list.php'),
                array('name' => 'Todo', 'url' => '/recipe/recipe_search.php'),
            ),
        ),
        'healthy' => array(
            'name' => '中医理论',
            'class_name' => 'mdi mdi-yin-yang',
            'sub_pages' => array(
                array('name' => '中医知识', 'url' => '/todo/todopage'),
            ),
        ),
    );

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
