<?php

class Conf_Recipe_Category
{
    /*
     * set_use 意思为： 使用在套餐配置中
     */
    private static $CATEGORIES = array(
        '饺子' => array('parent' => ''),
        '肉饺子' => array('parent' => '饺子', 'set_use' => 1),
        '素饺子' => array('parent' => '饺子', 'set_use' => 1),

        '馄饨' => array('parent' => ''),
        '肉馄饨' => array('parent' => '馄饨', 'set_use' => 1),
        '素馄饨' => array('parent' => '馄饨', 'set_use' => 1),

        '包子馅饼' => array('parent' => ''),
        '肉包子馅饼' => array('parent' => '包子馅饼', 'set_use' => 1),
        '肉包子' => array('parent' => '肉包子馅饼'),
        '肉馅饼' => array('parent' => '肉包子馅饼'),
        '素包子馅饼' => array('parent' => '包子馅饼', 'set_use' => 1),
        '素包子' => array('parent' => '素包子馅饼'),
        '素馅饼' => array('parent' => '素包子馅饼'),

        '蔬菜摊饼' => array('parent' => '', 'set_use' => 1),

        '饼卷菜' => array('parent' => ''),
        '蛋卷' => array('parent' => '饼卷菜', 'set_use' => 1),
        '煎饼果子' => array('parent' => '饼卷菜', 'set_use' => 1),
        '春卷' => array('parent' => '饼卷菜', 'set_use' => 1),

        '汉堡' => array('parent' => '', 'set_use' => 1),

        '面' => array('parent' => ''),
        '汤面' => array('parent' => '面', 'set_use' => 1),
        '干面' => array('parent' => '面', 'set_use' => 1),
        '炸酱面' => array('parent' => '干面'),
        '拌面' => array('parent' => '干面'),
        '热干面' => array('parent' => '干面'),
        '打卤面' => array('parent' => '干面'),
        '意面' => array('parent' => ''),
        '炒面' => array('parent' => ''),
        '凉面' => array('parent' => '干面'),

        '汤粥' => array('parent' => '', 'set_use' => 1),
        '豆浆' => array('parent' => '汤粥'),
        '粥' => array('parent' => '汤粥'),
        '羹' => array('parent' => '汤粥'),
        '汤圆' => array('parent' => '汤粥'),
        '汤' => array('parent' => '汤粥'),
        '牛奶麦片' => array('parent' => '汤粥'),
        '糊' => array('parent' => '汤粥'),

        '薯' => array('parent' => '', 'set_use' => 1),

        '薯泥' => array('parent' => '', 'set_use' => 1),

        '主菜' => array('parent' => ''),
        '荤菜' => array('parent' => '主菜', 'set_use' => 1),
        '热荤菜' => array('parent' => '荤菜'),
        '凉荤菜' => array('parent' => '荤菜'),
        '加工肉' => array('parent' => '凉荤菜', 'set_use' => 1),
        '素菜' => array('parent' => '主菜', 'set_use' => 1),
        '热素菜' => array('parent' => '素菜'),
        '凉素菜' => array('parent' => '素菜'),

        '蛋' => array('parent' => '', 'set_use' => 1),

        '主食' => array('parent' => ''),
        '精主食' => array('parent' => '主食', 'set_use' => 1),
        '杂粮主食' => array('parent' => '主食', 'set_use' => 1),

        //--------------------------------------
        '糕点' => array('parent' => '', 'recommend'=>false),
        '月饼' => array('parent' => '糕点', 'recommend'=>false),
        '意大利面' => array('parent' => '', 'recommend'=>false),
        '比萨' => array('parent' => '', 'recommend'=>false),
        '三明治' => array('parent' => '', 'recommend'=>false),
        '面片' => array('parent' => '', 'recommend'=>false),
        '疙瘩' => array('parent' => '', 'recommend'=>false),
        '炒饭' => array('parent' => '', 'recommend'=>false),
        '烩饭' => array('parent' => '', 'recommend'=>false),
        '炒饼' => array('parent' => '', 'recommend'=>false),
        '蒸饺' => array('parent'=>''),
        '米线' => array('parent' => ''),
        '猫耳朵' => array('parent' => ''),
        '面包' => array('parent' => ''),
        '寿司' => array('parent' => ''),
        '烧麦' => array('parent' => ''),
        '锅贴' => array('parent' => ''),
        '火烧' => array('parent' => ''),
        '小笼包' => array('parent' => ''),
        '肉夹馍' => array('parent' => ''),
        '肉龙' => array('parent' => ''),
    );

    private static $MAIN_CATE_FOR_VIEW = array(
        '荤菜' => array('荤菜'),
        '素菜' => array('素菜'),
        '主食' => array('主食'),
        '汤粥' => array('汤粥'),
        '饺子' => array('饺子'),
        '包子' => array('肉包子','素包子'),
        '面' => array('面'),
    );

    public static function getCategoriesOfMainCate($mainCate)
    {
        assert(isset(self::$MAIN_CATE_FOR_VIEW[$mainCate]));

        //先取最上一级类别$MAIN_CATE_FOR_VIEW
        $topCates = array();
        foreach (self::$MAIN_CATE_FOR_VIEW as $_cateItems) {
            $topCates = array_merge($topCates, $_cateItems);
        }

        //每个一个大类的集合
        $topCatesMap = array();
        foreach (self::$CATEGORIES as $_cateName => $_item) {
            $_topCate = self::_getTopCateForCate($_cateName, $topCates);
            $topCatesMap[$_topCate][] = $_cateName;
        }

        //返回结果
        $result = array();
        $needTopCates = self::$MAIN_CATE_FOR_VIEW[$mainCate];
        foreach ($needTopCates as $_topCate) {
            $result = array_merge($result, $topCatesMap[$_topCate]);
        }
        return $result;
    }
    public static function _getTopCateForCate($category, $topCates)
    {
        if (empty($category)) {
            return false;
        }

        $res = false;
        do {
            $cateInfo = self::$CATEGORIES[$category];
            if (empty($cateInfo)) {
                return false;
            }
            if (in_array($category, $topCates)) {
                $res = $category;
                break;
            }
            $parent = $cateInfo['parent'];
            if (!$parent) {
                return false;
            }
            $category = $parent;
        }while(true);

        return $res;
    }


    public static function getAllCategories()
    {
        return array_keys(self::$CATEGORIES);
    }

    public static function getMainCateForView()
    {
        return self::$MAIN_CATE_FOR_VIEW;
    }

    public static function getCateForSetMeal($category)
    {
        if (empty($category)) {
            return false;
        }

        do {
            $cateInfo = array_key_exists($category, self::$CATEGORIES)? self::$CATEGORIES[$category]: array();
            if (empty($cateInfo)) {
                return false;
            }
            if (!empty($cateInfo['set_use']) && $cateInfo['set_use']) {
                $res = $category;
                break;
            }
            $parent = $cateInfo['parent'];
            if (!$parent) {
                return false;
            }
            $category = $parent;
        }while(true);

        return $res;
    }
}