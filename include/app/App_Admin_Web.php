<?php

/**
 * 管理运营后台 - Web基类
 */
class App_Admin_Web extends Base_App
{
    protected $lgmode;        //页面逻辑模式 -- pub (公开页面,不需登录); pri (私有页面,需要登录)
    protected $smarty;        //smarty 工具对象

    function __construct($lgmode = 'pri', $tmplpath = ADMIN_TEMPLATE_PATH)
    {
        $this->lgmode = $lgmode;
        $this->smarty = new Tool_Smarty($tmplpath);
    }

    protected function checkAuth()
    {
//        $this->_uid = $this->getLoginUid();
//        // 已登录状态处理
//        if ($this->_uid)
//        {
//            //获取用户信息
//            $this->_user = Admin_Api::getStaff($this->_uid);
//
    }

    protected function setCommonPara()
    {
        $this->smarty->assign('_imgHost', ADMIN_IMG_HOST);
        $this->smarty->assign("_wwwHost", ADMIN_HOST);
        $this->smarty->assign("_uid", $this->_uid);
        $this->smarty->assign("_user", $this->_user);

        if ($this->_uid)
        {
        }
    }

    protected function showError($ex)
    {
        $error = "[" . $ex->getCode() . "]: " . $ex->getMessage();
        if ($ex->reason)
        {
            $error .= ' ' . $ex->reason;
        }
        echo $error . "\n";

        print_r($ex->getTrace());
        echo "\n";
    }

    protected function getLoginUid()
    {
        $verify = Tool_Input::clean('c', '_admin_session', TYPE_STR);
        $uid = Admin_Auth_Api::checkVerify($verify, Conf_Base::WEB_TOKEN_EXPIRED);
        if ($uid !== FALSE)
        {
            return $uid;
        }

        self::clearVerifyCookie();

        return FALSE;
    }

    protected function setSessionVerifyCookie($token, $expiredTime = 0)
    {
        $expiredTime = !empty($expiredTime) ? (time() + $expiredTime) : 0;
        setcookie("_admin_session", $token, $expiredTime, "/", Conf_Base::getAdminHost());
        setcookie('_admin_uid', $this->_uid, $expiredTime, '/', Conf_Base::getAdminHost());
    }

    protected static function clearVerifyCookie()
    {
        setcookie('_admin_session', '', -86400, '/', Conf_Base::getAdminHost());
        setcookie('_admin_uid', '', -86400, '/', Conf_Base::getAdminHost());

        setcookie('_admin_session', '', -86400, '/', Conf_Base::getBaseHost());
        setcookie('_admin_uid', '', -86400, '/', Conf_Base::getBaseHost());
    }

    protected function delegateTo($path)
    {
        chdir(ADMIN_HTDOCS_PATH . "/" . dirname($path));

        require_once ADMIN_HTDOCS_PATH . "/" . $path;
    }

}
