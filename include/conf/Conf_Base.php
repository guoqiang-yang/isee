<?php

/**
 * 基本配置
 */
class Conf_Base
{
    const
        WEB_TOKEN_EXPIRED = 43200,      //半天
        H5_TOKEN_EXPIRED = 8640000;     //100天


    /**
     * 通用status
     */
    const
        STATUS_NORMAL = 0,      //正常
        STATUS_DELETED = 1,     //删除
        STATUS_BANNED = 2,      //封禁
        STATUS_CANCEL = 3,      //取消
        STATUS_OFFLINE = 4,     //下线
        STATUS_WAIT_AUDIT = 5,  //未审核
        STATUS_UN_AUDIT = 6,    //驳回
        STATUS_ALL = 127;       //全部

    /**
     * 性别
     */
    const
        SEX_UNKNOWN = 0,
        SEX_MALE = 1,
        SEX_FEMALE = 2;

    /**
     * 注册来源
     */
    const
        REG_SOURCE_WEIXIN = 'weixin';

    /**
     * 用户关注来源
     */
    const
        SUBSCRIBE_SRC_CARD      = 'card',       //名片分享
        SUBSCRIBE_SRC_QR_CODE   = 'qr_code',    //扫描二维码
        SUBSCRIBE_SRC_CONTENT   = 'content',    //内容
        SUBSCRIBE_SRC_PAID      = 'paid',       //支付后关注
        SUBSCRIBE_SRC_OTHER     = 'other';      //其他

    public static function getEnvFlag()
    {
        return ENV;
        //return IN_DEV? 'test': 'prod';
        //return 'prod';
    }
    public static function isTestEnv()
    {
        return ENV != 'prod'? true: false;
    }

    public static function getCssJsHost()
    {
        return H5_HOST;
    }

    public static function getMainHost()
    {
        return H5_HOST;
    }

    public static function getBaseHost()
    {
        return BASE_HOST;
    }

    public static function getAdminHost()
    {
        return ADMIN_HOST;
    }

    public static function getH5Host()
    {
        return H5_HOST;
    }
}
