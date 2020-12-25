<?php

class Access_Weixin_User
{
    const USER_USERINFO = 'https://api.weixin.qq.com/cgi-bin/user/info';

    public static function getUserInfo($openId, $token)
    {
        $params = array(
            'access_token' => $token,
            'openid' => $openId,
            'lang' => 'zh_CN',
        );

        $userInfo = json_decode(Tool_Http::get(self::USER_USERINFO, $params), true);

        return isset($userInfo['errcode'])? array(): $userInfo;
    }
}