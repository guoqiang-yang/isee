<?php

class Access_Weixin_Auth 
{
    //默认回调地址@todo-change
    const REDIRECT_URI = 'http://m.shi.hhshou.cn/access/weixin_demo.php';
    //授权
    const AUTH_AUTHORIZE = 'https://open.weixin.qq.com/connect/oauth2/authorize';
    const AUTH_AUTH_TOKEN = 'https://api.weixin.qq.com/sns/oauth2/access_token';      //页面授权，获取token
    const AUTH_ACCESS_TOKEN = 'https://api.weixin.qq.com/cgi-bin/token';

    public static function auth($redirectUri = '')
    {
        $account = Access_Config::get(Conf_Base::REG_SOURCE_WEIXIN);

        //据说：下面这个数组顺序很重要，别乱动
        $params = array(
            'appid'. '='. $account['app_id'],
            'redirect_uri'. '='. (!empty($redirectUri)? urlencode($redirectUri): self::REDIRECT_URI),
            'response_type'. '='. 'code',
            'scope'. '='. 'snsapi_base',
            'state'. '='. Access_Const::WEIXIN_AUTH_STATUS_FLAG. '#wechat_redirect',
        );
        $authUrl = self::AUTH_AUTHORIZE. '?'. implode('&', $params);

        header('Location: '. $authUrl);
        exit;
    }

    public static function getAuthToken($code)
    {
        $account = Access_Config::get(Conf_Base::REG_SOURCE_WEIXIN);
        $params = array(
            'appid' => $account['app_id'],
            'secret' => $account['app_key'],
            'code' => $code,
            'grant_type' => 'authorization_code',
        );
        $tokenInfo = json_decode(Tool_Http::get(self::AUTH_AUTH_TOKEN, $params), true);

        return !empty($tokenInfo['errcode'])? array(): $tokenInfo;
    }

    public static function getAccessToken()
    {
        $accessTokenFileName = __DIR__. '/token/weixin_access_token.'. Conf_Base::getEnvFlag(). '.txt';

        $curTime = date('Y-m-d H:i:s');
        if (file_exists($accessTokenFileName)){
            $localAccessToken = file_get_contents($accessTokenFileName);
            $localAccessToken = json_decode($localAccessToken, true);
            $expiredAndReadOnly = $localAccessToken['expired'] < $curTime && $localAccessToken['flag'] == 'r';

            //直接返回token：未过期 or （过期&&只读）
            if (!empty($localAccessToken) && count($localAccessToken) == 3
                && ($localAccessToken['expired'] >= $curTime || $expiredAndReadOnly)) {
                return $localAccessToken['access_token'];
            }
        }

        //过期&&读写：锁文件
        //@notice(todo): 非分布式锁，后期可以借助redis实现 && 单机也可能出现并发覆盖问题
        if (!empty($localAccessToken) && count($localAccessToken) == 3
            && $localAccessToken < $curTime && $localAccessToken['flag'] == 'rw') {
            $localAccessToken['flag'] = 'r';
            file_put_contents($accessTokenFileName, json_encode($localAccessToken));
        }

        $account = Access_Config::get(Conf_Base::REG_SOURCE_WEIXIN);
        $params = array(
            'appid' => $account['app_id'],
            'secret' => $account['app_key'],
            'grant_type' => 'client_credential',
        );
        $tokenInfo = json_decode(Tool_Http::get(self::AUTH_ACCESS_TOKEN, $params), true);

        $accessToken = '';
        $localAccessToken['flag'] = 'rw';   //释放锁
        if (empty($tokenInfo['errcode'])) {
            $localAccessToken['access_token'] = $tokenInfo['access_token'];
            $localAccessToken['expired'] = time() + $tokenInfo['expires_in'] - 15*60; //提前15min 触发更新

            $accessToken = $tokenInfo['access_token'];
        }
        file_put_contents($accessTokenFileName, json_encode($localAccessToken));

        return $accessToken;
    }
}