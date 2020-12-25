<?php

/**
 * 荤素
 * @todo: 整理成按营养学分类？
 */
class Conf_Material_MV
{

    const VEGETABLE = 1;
    const MEAT = 2;
    public static $categoryMV = array(
        '猪肉类' => Conf_Material_MV::MEAT, '牛肉类' => Conf_Material_MV::MEAT, '羊肉类' => Conf_Material_MV::MEAT, '其它肉类' => Conf_Material_MV::MEAT, '鸡肉类' => Conf_Material_MV::MEAT, '鸭肉类' => Conf_Material_MV::MEAT,

        '虾类' => Conf_Material_MV::MEAT, '蟹类' => Conf_Material_MV::MEAT, '淡水鱼类' => Conf_Material_MV::MEAT, '咸水鱼类' => Conf_Material_MV::MEAT, '其它水产类' => Conf_Material_MV::MEAT,

        '蛋、奶' => Conf_Material_MV::VEGETABLE, '海菜类' => Conf_Material_MV::VEGETABLE, '豆类(制品)' => Conf_Material_MV::VEGETABLE, '茎叶类' => Conf_Material_MV::VEGETABLE, '根茎类' => Conf_Material_MV::VEGETABLE, '菌藻类' => Conf_Material_MV::VEGETABLE, '水生蔬菜' => Conf_Material_MV::VEGETABLE, '果实类' => Conf_Material_MV::VEGETABLE, '瓜菜类' => Conf_Material_MV::VEGETABLE, '酸菜类' => Conf_Material_MV::VEGETABLE,

        '水果' => Conf_Material_MV::VEGETABLE, '干果' => Conf_Material_MV::VEGETABLE, '谷类' => Conf_Material_MV::VEGETABLE,

        '其他' => Conf_Material_MV::VEGETABLE,
    );
    public static $dglCategoryFreshVegetable = array(
        '深色蔬菜' => Conf_Material_MV::VEGETABLE, '浅色蔬菜' => Conf_Material_MV::VEGETABLE,
    );

    public static function hasMeat(array $categories)
    {
        $categories = array_values(array_filter($categories));
        if (empty($categories))
        {
            return false;
        }

        foreach ($categories as $_category)
        {
            if (self::isMeatCategory($_category))
            {
                return true;
            }
        }
        return false;
    }

    public static function hasFreshVegetableDGL(array $categories)
    {
        if (empty($categories))
        {
            return false;
        }

        foreach ($categories as $_category)
        {
            if (!empty(self::$dglCategoryFreshVegetable[$_category]))
            {
                return true;
            }
        }
        return false;
    }

    public static function isMeatCategory($category)
    {
        assert(isset(self::$categoryMV[$category]));
        if (self::MEAT == self::$categoryMV[$category])
        {
            return true;
        } else
        {
            return false;
        }
    }
}
