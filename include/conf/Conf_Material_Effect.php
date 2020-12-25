<?php

class Conf_Material_Effect
{
    const TYPE_CONSTITUTION = 'constitution';    //体质
    const TYPE_EXPECT = 'expect';                //期望


    public static function getEffectTypeForCalculate()
    {
        return array(
            array(
                'type' => self::TYPE_CONSTITUTION,
                'weight' => 0.5
            ),
            array(
                'type' => self::TYPE_EXPECT,
                'weight' => 0.5
            ),
        );
    }

    public static function getEffectNameForShow()
    {
        return array(
            'disease' => array(
                '高血糖', '高血压', '高血脂', '胃炎', '口腔溃疡', '消化不良', '便秘', '肾亏', '失眠健忘',
            ),
            'expect' => array(
                '排毒养颜', '延缓衰老', '润肤抗皱', '美白祛斑', '增强免疫力', '减肥瘦身',
                '手术恢复', '清热去火', '养胃健脾', '补血', '养肝', '养肺',
            ),
        );
    }
}