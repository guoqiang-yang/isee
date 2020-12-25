<?php

/**
 * 频率相关
 */
class Conf_Freq
{
    //食材使用频率：>=每天1次;  每周4~6次；每周1~3次；月1~3次；<月1次；不吃
    const D_3 = '21',  //不限
        D_1 = '7',  //>=每天1次
        W4_6 = '5',  //每周4~6次
        W1_3 = '2',  //每周1~3次
        M1_3 = '0.5',  //月1~3次
        Y1_11 = '0.1',//<月1次(年1~11次)
        NO = '0';      //几乎不吃

    //各营养分类使用频率范围（次数/周）
    // min: 该类别的总次数
    // materialMax: 单个食材的最大频率
    private static $categoryWeekFreq = array(
        "全谷" => array("min" => 14, "materialMax" => self::D_3),
        "杂豆" => array("min" => 2, "materialMax" => self::W4_6),
        "薯" => array("min" => 3, "materialMax" => self::W4_6),
        "精米面" => array("min" => 7, "materialMax" => self::D_3),
        "深色蔬菜" => array("min" => 7, "materialMax" => self::W4_6),
        "浅色蔬菜" => array("min" => 7, "materialMax" => self::W4_6),
        "水果" => array("min" => 7, "materialMax" => self::W4_6),
        "大豆" => array("min" => 3, "materialMax" => self::D_1),
        "奶" => array("min" => 7, "materialMax" => self::D_1),
        "畜" => array("min" => 3, "materialMax" => self::W4_6),
        "禽" => array("min" => 2, "materialMax" => self::W4_6),
        "水产" => array("min" => 2, "materialMax" => self::W4_6),
        "蛋" => array("min" => 7, "materialMax" => self::D_1),
        "坚果" => array("min" => 7, "materialMax" => self::W4_6)
    );

    public static function getCategoryFreqOfWeek()
    {
        return self::$categoryWeekFreq;
    }

    public static function popularityRankToFreq($rank)
    {
        if ($rank >=8 ) {
            $freq = self::D_1;
        }elseif ($rank >=6 ) {
            $freq = self::W4_6;
        }elseif ($rank >=7 ) {
            $freq = self::W1_3;
        }elseif ($rank >=2 ) {
            $freq = self::M1_3;
        //}elseif ($rank >=1 ) {
        //    $freq = self::Y1_11;
        }else{
            $freq = self::Y1_11;
        }
        return $freq;
    }
}