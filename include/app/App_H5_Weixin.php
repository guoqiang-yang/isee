<?php

/**
 * 微信网页基类-添加了一个参数：_openid
 */

class App_H5_Weixin extends App_H5_Page
{

    protected $_openid;
    protected $_weixinInfo;

    function __construct($lgmode)
    {
        parent::__construct($lgmode);

    }

    protected function checkAuth()
    {
        parent::checkAuth();

        if (!empty($_REQUEST['code']))
        {
            $this->_openid = WeiXin_Api::getOpenIdByCode($_REQUEST['code']);
            if (!empty($this->_openid))
            {
                $accessToken = WeiXin_Api::getAccessToken();
                $this->_weixinInfo = WeiXin_Api::getUserInfo($this->_uid, $this->_openid, $accessToken);
            }

            session_start();
            $_SESSION['openid'] = $this->_openid;
        }
        else
        {
            session_start();
            if (!empty($_SESSION['openid']))
            {
                $accessToken = WeiXin_Api::getAccessToken();
                $this->_openid = $_SESSION['openid'];
                $this->_weixinInfo = WeiXin_Api::getUserInfo($this->_uid, $this->_openid, $accessToken);
            }
            else if (empty($_REQUEST['status']) && empty($_REQUEST['code']) && WeiXin_Api::isWx())
            {
                $http = $_SERVER['HTTPS'] == 'on' ? 'https:://' : 'http://';
                $referer =  $http . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];

                WeiXin_Api::auth($referer);
            }
        }
    }
}