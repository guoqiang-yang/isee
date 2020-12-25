<?php

class Conf_Set_Meal
{
    /**
     * @var array 套餐配置
     *
     * 写法规则：
     * (1) 菜数量：荤菜:2, (没有数字默认为1)
     * (2) 根据前面判断： *素菜  如果已经有蔬菜就不再选，  *蛋类 如果已经有蛋类就不再选
     * (3) 二选一：素菜||沙拉。
     *      ||: 先找菜谱再二选一
     *      | : 先二选一再找菜谱
     */
    private static $SET_DETAIL = array(
//饺子为主
        //'饺子肉' => array('肉饺子', '素菜', '汤粥'),
        '饺子肉' => array('肉饺子'),
        //'饺子素' => array('素饺子', '蛋', '加工肉', '汤粥'),
        '饺子素' => array('素饺子'),

//馄饨为主
        //'馄饨肉' => array('肉馄饨', '素菜', '汤粥'),
        //'馄饨素' => array('素馄饨', '蛋', '薯', '加工肉', '汤粥'),
        '馄饨肉' => array('肉馄饨', '汤粥'),
        '馄饨素' => array('素馄饨', '薯'),

//包子馅饼为主
        //'包子馅饼肉' => array('肉包子馅饼', '素菜', '蛋', '汤粥'),
        //'包子馅饼素' => array('素包子馅饼', '蛋', '加工肉', '汤粥'),
        '包子馅饼肉' => array('肉包子馅饼','汤粥'),
        '包子馅饼素' => array('素包子馅饼','汤粥'),

//蔬菜饼为主
        //'蔬菜摊饼' => array('蔬菜摊饼', '*薯', '*蛋', '汤粥'),
        '蔬菜摊饼' => array('蔬菜摊饼', '汤粥'),

//汉堡为主
        //'汉堡' => array('汉堡', '沙拉', '*蛋', '*加工肉', '*薯', '汤粥'),
        '汉堡' => array('汉堡', '沙拉', '汤粥'),

//饼卷菜为主
        '蛋卷' => array('蛋卷', '*素菜||沙拉', '*蛋', '*加工肉', '*薯', '汤粥'),
        '春卷' => array('春卷', '*素菜', '*蛋', '*加工肉', '*薯', '汤粥'),
        '煎饼果子' => array('煎饼果子', '*素菜||沙拉', '*蛋', '*加工肉', '*薯', '汤粥'),

//面为主
        //'汤面' => array('汤面', '素菜', '*蛋', '*加工肉'),
        //'干面' => array('干面', '素菜', '*蛋', '*加工肉', '汤粥'),
        '汤面' => array('汤面'),
        '干面' => array('干面', '汤粥'),

//薯为主
        //'薯泥' => array('薯泥', '*素菜||沙拉', '*蛋', '*加工肉', '汤粥'),
        '薯泥' => array('薯泥', '汤粥'),

//主菜为主
        '二菜1' => array('荤菜', '素菜', '精主食:1|杂粮主食:2'),
        //'二菜2' => array('荤菜', '素菜', '薯', '精主食:1|杂粮主食:2'),
        '二菜2' => array('荤菜', '素菜', '精主食:1|杂粮主食:2'),
        '二菜3' => array('荤菜', '素菜', '汤粥', '精主食:1|杂粮主食:2'),
        //'二菜4' => array('荤菜', '素菜', '汤粥', '薯', '精主食:1|杂粮主食:2'),
        '二菜4' => array('荤菜', '素菜', '汤粥', '精主食:1|杂粮主食:2'),

        '一荤1' => array('荤菜', '汤粥', '精主食:1|杂粮主食:2'),
        '一素1' => array('素菜', '汤粥', '精主食:1|杂粮主食:2'),
        '一素2' => array('素菜', '杂粮主食', '汤粥'),
    );

    /**
     * 一日三餐套餐配置
     * todo: 每个人是不一样的
     * @var array
     */
    private static $SET_LIST = array(
        Conf_Recipe::BREAKFAST => array('饺子肉' => 1, '饺子素' => 1, '蔬菜摊饼' => 3,/*'薯泥' => 3, '馄饨素' => 1, '馄饨肉' => 1, */ '汤面' => 3, '包子馅饼肉' => 1, '包子馅饼素' => 2, /*'蛋卷' => 1, '春卷' => 0, '煎饼果子' => 0, '一素2' => 3*/),
        Conf_Recipe::LUNCH => array('二菜1' => 10, '二菜2' => 10, /*'二菜3' => 10, '二菜4' => 10, '饺子肉' => 1, '饺子素' => 1, '包子馅饼肉' => 0, '包子馅饼素' => 0, '汤面' => 1, '干面' => 0*/),
        Conf_Recipe::DINNER => array('一荤1' => 5, '一素1' => 5, /*'二菜1' => 5, '二菜2' => 5, '二菜3' => 5, '二菜4' => 5,*/ '饺子肉' => 4, '饺子素' => 4, '包子馅饼肉' => 2, '包子馅饼素' => 2, '汤面' => 3, '干面' => 3),
    );
    /**
     * @var array 最多可以有几种。例如，一周最多吃两种饺子/包子，每种饺子/包子最多吃2次。
     */
    private static $MAX_TIMES_PER_WEEK = array(
        array('sets' => array('饺子肉','饺子素','包子馅饼肉','包子馅饼素'),
            'max_set_num' => 2,
            'max_times_per_recipe'=>3),
    );

    /**
     * 获取某个菜谱类型的推荐(在计划中使用的)数量限制值
     * @param $setName
     * @param $days
     * @return bool|mixed
     */
    public static function getLimitOfSet($setName, $days)
    {
        static $limitMap = array();
        if (empty($limitMap)) {
            foreach (self::$MAX_TIMES_PER_WEEK as $_idx => $_item) {
                foreach ($_item['sets'] as $_set) {
                    $limitMap[$_set] = $_idx;
                }
            }
        }

        if (isset($limitMap[$setName]))
        {
            $idx = $limitMap[$setName];
            $limitConf = self::$MAX_TIMES_PER_WEEK[$idx];
            $limitConf['max_set_num'] = round($days*$limitConf['max_set_num']/7);
            return $limitConf;
        }

        return false;
    }

    public static function getAllSets()
    {
        return self::$SET_LIST;
    }

    public static function getRecipeSetOfType($type)
    {
        assert(isset(self::$SET_LIST[$type]));
        return self::$SET_LIST[$type];
    }

    public static function ifSetInType($setName, $type)
    {
        return isset(self::$SET_LIST[$type][$setName]);
    }

    public static function getSet($name)
    {
        if (empty(self::$SET_DETAIL[$name]))
        {
            return false;
        }
        return self::$SET_DETAIL[$name];
    }

    /**
     * 获取某个套餐包含的食谱分类
     *
     * @param $setName
     * @return array
     */
    public static function getCategoriesOfSet($setName)
    {
        $resCategories = array();
        $_setCategories = self::$SET_DETAIL[$setName];
        foreach ($_setCategories as $_category) {
            if ('*' == $_category[0]) {
                $_category = substr($_category, 1);
            }
            if (false !== strpos($_category, '||')) {
                $_cateMap = Tool_Array::s2a($_category, '||');
                $resCategories = array_merge($resCategories, array(array_keys($_cateMap)));
            }elseif (false !== strpos($_category, '|')) {
                $_cateMap = Tool_Array::s2a($_category, '|');
                $resCategories = array_merge($resCategories, array(array_keys($_cateMap)));
            }else{
                $resCategories[] = $_category;
            }
        }
        $resCategories = array_values(array_filter($resCategories));
        return $resCategories;
    }


    //与上面不同的是，类别带着属性(比如是否必选等)
    public static function getCategoriesOfSetEx($setName)
    {
        $resCategories = array();
        $_setCategories = self::$SET_DETAIL[$setName];
        foreach ($_setCategories as $_category) {
            $required = true;
            if ('*' == $_category[0]) {
                $_category = substr($_category, 1);
                $required = false;
            }
            if (false !== strpos($_category, '||')) {
                $_cateMap = Tool_Array::s2a($_category, '||');
                $tmpCategories = array_keys($_cateMap);
            }elseif (false !== strpos($_category, '|')) {
                $_cateMap = Tool_Array::s2a($_category, '|');
                $tmpCategories = array_keys($_cateMap);
            }else{
                $tmpCategories = array($_category);
            }
            $resCategories[]= array('required' => $required, 'categories' => $tmpCategories);
        }
        $resCategories = array_values(array_filter($resCategories));
        return $resCategories;
    }

    /**
     * 返回 "分类集合" 覆盖的 "套餐集合"
     * 注意：*可选的不考虑，直接认为覆盖。 即：仅记录必选的cate。
     *
     * @param $catesIndex 格式 {cate => freq}
     * @return array('sets'=>, 'cateSetsIndex'=>)
     */
    public static function getSetFromCates($catesIndex)
    {
        $resultSet = $cateSetsIndex = array();

        foreach (self::$SET_DETAIL as $_set => $_cates)
        {
            $ok = true;
            $_processedCates = array();
            foreach ($_cates as $_category) {

                //处理* (表示可选)
                if ('*' == $_category[0]) {
                    continue;
                }

                //处理| (表示或)
                $_categories = array();
                if (false !== strpos($_category, '||')) {   //或：先找菜谱再二选一
                    $_categories = Tool_Array::s2a($_category,'||');
                } elseif (false !== strpos($_category, '|')) {   //二选一再找菜谱
                    $_categories = Tool_Array::s2a($_category, '|');
                }

                //记录
                if (!empty($_categories)) {
                    $ok = false;
                    foreach ($_categories as $_c=>$_tmp) {
                        $_processedCates[$_c] = 1;
                        if ( !$ok && !empty($catesIndex[$_c]) ) {
                            $ok = true;
                        }
                    }
                }else{
                    $_processedCates[$_category] = 1;
                    if ( empty($catesIndex[$_category]) ) {
                        $ok = false;
                    }
                }
                if (!$ok) break;
            }//end foreach

            if ($ok) {
                $resultSet[$_set]=1;
                foreach($_processedCates as $_cate => $_tmp) {
                    $cateSetsIndex[$_cate][$_set] = 1;
                }
            }
        }//end foreach

        return array($resultSet, $cateSetsIndex);
    }

    public static function getCateSetIndex($excludeOptional = true)
    {
        $cateSet = array();

        foreach (self::$SET_DETAIL as $_set => $_cates)
        {
            foreach ($_set as $_category) {

                //处理* (表示可选)
                if ('*' == $_category[0]) {
                    $_category = substr($_category, 1);
                    if ($excludeOptional) {
                        continue;
                    }
                }

                //处理| (表示或)
                $_categories = array();
                if (false !== strpos($_category, '||')) {   //或：先找菜谱再二选一
                    $_categories = explode('||', $_category);
                } elseif (false !== strpos($_category, '|')) {   //二选一再找菜谱
                    $_categories = Tool_Array::s2a($_category, '|');
                }

                //记录
                if (!empty($_categories)) {
                    foreach ($_categories as $_c) {
                        $cateSet[$_c][$_set] = 1;
                    }
                }else{
                    $cateSet[$_category][$_set] = 1;
                }
            }
        }

        return $cateSet;
    }

    public static function parseRecipeCate($cate)
    {
        $required = true;
        if ('*' == $cate[0])
        {
            $cate = substr($cate, 1);
            $required = false;
        }
        if (false !== strpos($cate, '||'))
        {
            //或：先找菜谱再二选一
            $_categories = explode('||', $cate);
            $idx = array_rand($_categories);
            $cate = $_categories[$idx];
        }
        elseif (false !== strpos($cate, '|'))
        {
            //二选一再找菜谱
            $_categories = Tool_Array::s2a($cate, '|');
            $cates = Tool_Array::randByWeight($_categories, 1);
            reset($cates);
            $cate = key($cates);
        }
        return array('cate' => $cate, 'required' => $required);
    }


}