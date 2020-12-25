<?php

/**
 * 菜谱tags类【注意：暂时线上未用】
 */
class Conf_Recipe_Tag
{
    private static $TAGS_BASE = array(
        '荤菜','素菜','热菜','凉菜','粥','汤羹','茶','汁','饮','膏','糊','面食','小吃','糕点','水果','罐头','主食'
    );

    private static $TAG_SETS = array(
        '早餐' => array('早餐'),//暂时去掉：,'面食','小吃','糕点','主食'),
        '正餐' => array('家常菜'),
        '外国菜' => array('西餐','东南亚','日韩'),
    );

    private static $TAG_SETS_FILTER = array(
        '早餐' => array('粥','糊','饮','羹','酱','汤','汤羹','烘焙','面包','炸','凉面','糕点'),
        '正餐' => array('粥','糊','饮','羹','酱','汤','汤羹','烘焙','面包','炸','早餐',
            '茶','汁','膏','面食','小吃','糕点','水果','罐头','主食','点心','烧烤'),
    );

    private static $FILTER_COMMON_TAGS = array('外国菜','便当','火锅', '西餐','东南亚','日韩','炸','烘焙','蛋糕','烧烤');

    public static function getBaseTags()
    {
        return self::$TAGS_BASE;
    }

    public static function getTagSets()
    {
        return array_keys(self::$TAG_SETS);
    }

    public static function getTagsOfSet($set)
    {
        return isset(self::$TAG_SETS[$set]) ? self::$TAG_SETS[$set] : array();
    }

    //是否家常菜
    public static function isHomeCooking(array $tags, $strict=true)
    {
        //过滤掉标签
        foreach($tags as $_tag) {
            if ($strict && self::ifStrictNeedFilter($_tag, '正餐')) {
                return false;
            }
        }

        $mainTags = array('家常菜');
        foreach ($mainTags as $_tag) {
            if (in_array($_tag, $tags)) {
                return true;
            }
        }
        return false;
    }

    //是否是汤羹粥
    public static function isSoup(array $tags)
    {
        //过滤掉标签
        $soupTags = array('粥','汤羹','糊');
        foreach ($soupTags as $_tag) {
            if (in_array($_tag, $tags)) return true;
        }
        return false;
    }

    //是否是主食
    public static function isStaple(array $tags)
    {
        //过滤掉标签
        $stapleTags = array('早餐','面食','小吃','糕点','主食');
        foreach ($stapleTags as $_tag) {
            if (in_array($_tag, $tags)) return true;
        }
        return false;
    }

    public static function isBreakfast(array $tags, $strict=true)
    {
        //过滤掉标签
        if (self::isSoup($tags)) {
            return false;
        }

        //过滤掉标签
        foreach($tags as $_tag) {
            if ($strict && self::ifStrictNeedFilter($_tag, '早餐')) {
                return false;
            }
        }

        //符合的标签
        $breakfastTags = array('早餐');
        foreach ($breakfastTags as $_tag) {
            if (in_array($_tag, $tags)) {
                return true;
            }
        }
        return false;
    }

    public static function getFilterTags()
    {
        return self::$FILTER_COMMON_TAGS;
    }

    public static function getFiterTagsOfSet($set)
    {
        $tags = self::$TAG_SETS_FILTER;
        return isset($tags[$set]) ? $tags[$set]:array();
    }

    public static function ifStrictNeedFilter($tag, $set='')
    {
        $filterTags1 = self::getFilterTags();
        if (in_array($tag, $filterTags1)) {
            return true;
        }
        if ($set) {
            $filterTags2 = self::getFiterTagsOfSet($set);
            if (in_array($tag, $filterTags2)) {
                return true;
            }
        }
        return false;
    }
}