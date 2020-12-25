<?php

class Access_Common
{

    /**
     * 获取用户的所在的平台.
     *
     * @return string
     */
    public static function getScene()
    {
        if (!empty($_COOKIE['_testhls_uid'])) {
            return Conf_Base::REG_SOURCE_WEIXIN; //@todo 测试使用
        }

        if (self::isWeixin()) {
            return Conf_Base::REG_SOURCE_WEIXIN;
        }


        return '';
    }

    /**
     * 是否为Weixin平台请求.
     *
     * @return bool
     */
    public static function isWeixin()
    {
        return isset($_SERVER['HTTP_USER_AGENT'])
                && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false ? true: false;
    }

}