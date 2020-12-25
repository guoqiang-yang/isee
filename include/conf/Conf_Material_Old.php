<?php

/**
 * 旧的食材分类（从网络整理。后来都用了膳食指南分类）
 * @todo: 还要么？  比较细的猪肉类、牛肉类，可能也有用，只是暂时未用。
 */
class Conf_Material_Old
{
    //传统食材分类(对比：营养学食材分类)
    public static $topCategories = array(
        '肉类' => array('猪肉类', '牛肉类', '羊肉类', '鸡肉类', '鸭肉类', '其它肉类'), '水产' => array('淡水鱼类', '咸水鱼类', '虾类', '蟹类', '其它水产类'), '蔬菜' => array('茎叶类', '瓜菜类', '根茎类', '果实类', '水生蔬菜', '菌藻类', '酸菜类'), '水果' => array('水果'), '其它' => array('蛋、奶', '豆类(制品)', '谷类', '干果'),
    );

    public static function getCategoriesOfTop($set)
    {
        return isset(self::$topCategories[$set]) ? self::$topCategories[$set] : array();
    }

    public static function getTopCategoryInfos()
    {
        return self::$topCategories;
    }

    public static function isTopCategory($set)
    {
        return isset(self::$topCategories[$set]);
    }

    public static function getTopCategories()
    {
        return array_keys(self::$topCategories);
    }

    public static function getTopCategoryOf($cate)
    {
        foreach (self::$topCategories as $_topCate => $_cates)
        {
            if (in_array($cate, $_cates))
            {
                return $_topCate;
            }
        }
        return false;
    }
}
