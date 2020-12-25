<?php

class Access_Weixin_AuthApi extends Access_AuthBase
{
    public function auth()
    {
        //静默授权：获取openID
        $openId = $this->getUserOpenId();

        //用openid查询用户的信息：是否关注/注册
        $scenePassportInfo = User_Api::sceneAuthorize($openId, Conf_Base::REG_SOURCE_WEIXIN);
        $uid = $scenePassportInfo['uid'];

        if (! $scenePassportInfo['is_subscribe']) { //未关注
            $accessToken = Access_Weixin_Auth::getAccessToken();
            $weixinUserinfo = Access_Weixin_User::getUserInfo($openId, $accessToken);

            if (!empty($weixinUserinfo) && $weixinUserinfo['subscribe'] == 1) {
                User_Api::sceneSubscribe($uid, $openId, Conf_Base::REG_SOURCE_WEIXIN,  $weixinUserinfo);
                $scenePassportInfo['is_subscribe'] = 1;
            }
        }

        $userInfo = User_Api::getUserInfoByUid($scenePassportInfo['uid']);
        $scenePassportInfo['verify'] = $userInfo['_verify'];
        $scenePassportInfo['scene'] = Conf_Base::REG_SOURCE_WEIXIN;

        return $this->setAuthResponse($scenePassportInfo);
    }

    //页面授权获取open_id
    private function getUserOpenId()
    {
        $code = !empty($_REQUEST['code'])? $_REQUEST['code']: '';

        if (empty($code)) {
            $redirectUrl = $this->getCurPageUrl();
            Access_Weixin_Auth::auth($redirectUrl);
            exit;
        }

        if (empty($_REQUEST['state']) || $_REQUEST['state'] != Access_Const::WEIXIN_AUTH_STATUS_FLAG) {
            throw new Exception('Auth Fail');
        }

        $tokenInfo = Access_Weixin_Auth::getAuthToken($code);

        if (empty($tokenInfo)) {
            throw new Exception('Fetch Token Fail');
        }

        return $tokenInfo['openid'];
    }


    private function getCurPageUrl()
    {
        return WEB_PROTOCOL . $_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI'];
    }
}