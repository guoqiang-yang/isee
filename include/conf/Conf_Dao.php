<?php

/**
 * Database Access Object 配置
 */
class Conf_Dao
{
    private static $_tables = array(
        't_recipe' => array(
            'pk' => 'id',
            'fields' => array('*'),
            'created_time' => 'ctime',
            'modified_time' => 'mtime',
        ),
        't_material' => array(
            'pk' => 'id',
            'fields' => array('*'),
            'created_time' => 'ctime',
            'modified_time' => 'mtime',
        ),
        't_recipe_material' => array(
            'pk' => 'id',
            'fields' => array('*'),
            'created_time' => 'ctime',
            'modified_time' => 'mtime',
        ),
        't_kvdb' => array(
            'pk' => 'id',
            'fields' => array('*'),
            'created_time' => 'ctime',
            'modified_time' => 'mtime',
        ),
        't_user_daily_recipes' => array(
            'pk' => 'id',
            'fields' => array('*'),
            'created_time' => 'ctime',
            'modified_time' => 'mtime',
        ),
        't_user_materials' => array(
            'pk' => 'id',
            'fields' => array('*'),
            'created_time' => 'ctime',
            'modified_time' => 'mtime',
        ),
        't_user_materials_shopping_list' => array(
            'pk' => 'id',
            'fields' => array('*'),
            'created_time' => 'ctime',
            'modified_time' => 'mtime',
        ),
        't_spider_log' => array(
            'pk' => 'id',
            'fields' => array('*'),
            'created_time' => 'ctime',
            'modified_time' => 'mtime',
        ),
        't_picture' => array(
            'pk' => 'id',
            'fields' => array('*'),
            'created_time' => 'ctime',
            'modified_time' => 'mtime',
        ),
        't_similar_recipes' => array(
            'pk' => 'id',
            'fields' => array('*'),
            'created_time' => 'ctime',
            'modified_time' => 'mtime',
        ),
        't_user' => array(
            'pk' => 'uid',
            'fields' => array('*'),
            'created_time' => 'ctime',
            'modified_time' => 'mtime',
        ),
        't_user_3rd_relation' => array(
            'pk' => 'id',
            'fields' => array('*'),
            'created_time' => 'ctime',
            'modified_time' => 'mtime',
        ),
        't_user_3rd_info' => array(
            'pk' => 'id',
            'fields' => array('*'),
            'created_time' => 'ctime',
            'modified_time' => 'mtime',
        ),
        't_user_constitution' => array(
            'pk' => 'id',
            'fields' => array('*'),
            'created_time' => 'ctime',
            'modified_time' => 'mtime',
        ),
        't_material_effect' => array(
            'pk' => 'id',
            'fields' => array('*'),
            'created_time' => 'ctime',
            'modified_time' => 'mtime',
        ),
    );

    public static function get($table)
    {
        assert(!empty(self::$_tables[$table]));

        return self::$_tables[$table];
    }
}