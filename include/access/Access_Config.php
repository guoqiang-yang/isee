<?php

class Access_Config
{

    public static function get($scene)
    {
        $env = Conf_Base::getEnvFlag();
        return !empty(self::$SCENE_ACCOUNTS[$scene][$env])? self::$SCENE_ACCOUNTS[$scene][$env]: array();
    }


    private static $SCENE_ACCOUNTS = array(
        Conf_Base::REG_SOURCE_WEIXIN => array(
            'test' => array(
                'app_id' => 'wxdaad7090b6fe4cbf',
                'app_key' => '3c12e811d4062a9b781dc806b21802f3',
            ),
            'prod' => array(
                'app_id' => 'wx34ea9f1724e57afe',
                'app_key' => '09dd502d12d4b0e1c5bd70f7718a1e3c',
            ),
        ),
    );



}