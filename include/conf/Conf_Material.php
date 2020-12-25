<?php

class Conf_Material
{
    //主材辅材
    //main  auxiliary
    const MAIN = 1, //主食材
        AUXILIARY = 2;  //辅食材

    /**
     * 营养学分类小类(from膳食指南分类)
     */
    private static $CATEGORIES = array(
        "全谷" => array("min" => 2, "max" => 5),
        "精米面" => array("min" => 1, "max" => 5),
        "薯" => array("min" => 2, "max" => 5),
        "杂豆" => array("min" => 2, "max" => 5),
        "深色蔬菜" => array("min" => 2, "max" => 10),
        "浅色蔬菜" => array("min" => 2, "max" => 10),
        "水果" => array("min" => 3, "max" => 10),
        "畜" => array("min" => 2, "max" => 5),
        "禽" => array("min" => 1, "max" => 5),
        "水产" => array("min" => 2, "max" => 5),
        "蛋" => array("min" => 1, "max" => 3),
        "奶" => array("min" => 1, "max" => 5),
        "大豆" => array("min" => 2, "max" => 5),
        "坚果" => array("min" => 3, "max" => 5)
    );

    /**
     * 营养学分类大类(from膳食指南分类)：头部菜单等用
     */
    private static $MAIN_CATE_FOR_VIEW = array(
        '谷薯杂豆' => array('全谷','精米面','薯','杂豆',),
        '蔬菜' => array('深色蔬菜','浅色蔬菜'),
        '畜禽' => array('畜','禽',),
        '水产' => array('水产'),
        '水果' => array('水果'),
        '蛋奶大豆' => array('蛋','奶','大豆'),
        '干果' => array('坚果')
    );

    /**
     * 营养学分类粗类(from膳食指南分类)：粗略评估用
     */
    private static $STAT_CATE_FOR_VIEW = array(
        '谷薯杂豆' => array('全谷', '精米面', '薯', '杂豆'),
        '蔬菜水果' => array('深色蔬菜', '浅色蔬菜', '水果'),
        '禽鱼肉蛋' => array('畜', '禽', '水产', '蛋'),
        '奶大豆坚果' => array('奶', '大豆', '坚果'),
    );

    public static function getMainCategoryForView($cate = '')
    {
        return !empty($cate)&&!empty(self::$MAIN_CATE_FOR_VIEW[$cate])?
                self::$MAIN_CATE_FOR_VIEW[$cate]: self::$MAIN_CATE_FOR_VIEW;
    }

    public static function getStatCategoryByDglCate($dglCate)
    {
        $matchStatCate = '';
        $statCates = self::$STAT_CATE_FOR_VIEW;
        foreach($statCates as $statCate => $subCates) {
            if (in_array($dglCate, $subCates)) {
                $matchStatCate = $statCate;
            }
        }
        return $matchStatCate;
    }

    /**
     * 是否是谷物
     *
     * @param $dglCategory
     * @return bool
     */
    public static function isCereal($dglCategory)
    {
        if ($dglCategory=='全谷' || $dglCategory=='精米面')
        {
            return true;
        }
        return false;
    }

    /**
     * 每个类别的食材数量阈值(下限,上限)
     * @param int $days
     * @return array
     */
    public static function getNumLimitOfCate($days = 7)
    {
        $conf = self::$CATEGORIES;
        if (7 != $days && $days) {

            foreach ($conf as $_cate => &$_numConf) {
                $_numConf['min'] = ceil($_numConf['min'] * $days / 7);
                $_numConf['max'] = ceil($_numConf['max'] * $days / 7);
            }
        }
        return $conf;
    }
}