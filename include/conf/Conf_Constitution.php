<?php

/**
 * 体质配置
 */
class Conf_Constitution
{
    const   SEPARATE_1  = ',',
            SEPAARATE_2 = ':';
    static $CONSTITUTIONS = array('平和质','气虚质','阳虚质','阴虚质','痰湿质','湿热质','血瘀质','气郁质','特禀质');

    public static function getConstitutions()
    {
        return self::$CONSTITUTIONS;
    }

    public static function isConstitution($val)
    {
        return in_array($val, self::$CONSTITUTIONS);
    }

    //解析：体质串: String => [[name=>'', weight=>''], ..., ]
    public static function parseConstitution($constitution)
    {
        $constitutionList = array();

        if (empty($constitution)) {
            return $constitutionList;
        }
        foreach(explode(self::SEPARATE_1, $constitution) as $weightInfo) {
            list($name , $weight) = explode(self::SEPAARATE_2, $weightInfo);

            $constitutionList[] = array(
                'name' => $name,
                'weight' => $weight,
            );
        }

        return $constitutionList;
    }

    public static function genConstitution($constitutionList)
    {
        if (!is_array($constitutionList)) {
            return '';
        }

        $tmp = array();
        foreach($constitutionList as $item) {
            $tmp[] = $item['name']. self::SEPAARATE_2. $item['weight'];
        }

        return implode(self::SEPARATE_1, $tmp);
    }


    public static function getConstitutionsIntro($flag)
    {
        $info = array(
            '平和质' => array(
                'img' => 'http://m.shi.hhshou.cn/i/pinghe.png',
                'con' => array(
                    '面色、肤色润泽，唇色红润',
                    '精力充沛，不易疲劳',
                    '耐受寒热，平时较少生病',
                    '舌色淡红，苔薄白，脉和有神',
                    '对自然环境和社会环境适应能力较强',
                ),
            ),
            '气虚质' => array(
                'img' => 'http://m.shi.hhshou.cn/i/qixu.png',
                'con' => array(
                    '平素语音低弱，气短懒言',
                    '容易疲乏，精神不振，易出汗',
                    '舌淡红，舌边有齿痕，脉弱',
                    '不耐受风、寒、暑，易患感冒',
                    '性格内向，不喜冒险',
                ),
            ),
            '阳虚质' => array(
                'img' => 'http://m.shi.hhshou.cn/i/yangxu.png',
                'con' => array(
                    '畏寒怕冷，手脚发凉，容易感冒',
                    '容易疲劳，稍微活动一下就会大汗淋漓、气喘吁',
                    '舌头胖胖的，周边有齿痕，舌苔颜色偏淡',
                    '比较安静内向，不大说话，不喜欢活动',
                ),
            ),
            '阴虚质' => array(
                'img' => 'http://m.shi.hhshou.cn/i/yinxu.png',
                'con' => array(
                    '手心热、足心热、心中烦热',
                    '口干、口苦、咽干、恶心',
                    '睡眠不安、盗汗、燥汗、遗精',
                    '大便干、小便黄或黄赤有热感',
                ),
            ),
            '痰湿质' => array(
                'img' => 'http://m.shi.hhshou.cn/i/tanshi.png',
                'con' => array(
                    '常见体形偏胖，腹部满且松软',
                    '面部皮肤油脂较多，多汗且黏，胸闷，痰多',
                    '舌头胖大，舌苔白腻，口黏腻或甜',
                    '性格偏温和，稳重，多善于忍耐',
                ),
            ),
            '湿热质' => array(
                'img' => 'http://m.shi.hhshou.cn/i/shire.png',
                'con' => array(
                    '皮肤油腻，容易长痘，口苦口干，唾液粘稠',
                    '身体困重倦怠，常感觉胸口闷闷不舒服',
                    '常感觉皮肤发烫，容易心烦急躁',
                    '舌质偏红，舌苔黄腻',
                ),
            ),
            '血瘀质' => array(
                'img' => 'http://m.shi.hhshou.cn/i/xueyu.png',
                'con' => array(
                    '肤色晦暗，色素沉着，容易出现瘀斑',
                    '口唇暗淡，舌黯或有瘀点，舌下脉络紫黯或增粗',
                    '头部、胸部、胁、小腹或四肢有时会疼痛',
                    '容易烦躁，健忘',
                ),
            ),
            '气郁质' => array(
                'img' => 'http://m.shi.hhshou.cn/i/qiyu.png',
                'con' => array(
                    '常常四肢乏力，不耐运动，容易疲劳',
                    '舌淡红，舌苔薄，颜色偏白',
                    '情感脆弱，容易烦闷不乐',
                    '性格内向不稳定，敏感多虑',
                ),
            ),
            '特禀质' => array(
                'img' => 'http://m.shi.hhshou.cn/i/tebing.png',
                'con' => array(
                    '常有遗传或先天家族性特征',
                    '对过敏季节适应能力差，易引发宿疾',
                ),
            ),
        );

        return array_key_exists($flag, $info)? $info[$flag]: $info;
    }

}
