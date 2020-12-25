<?php

/**
 * 管理运营后台 - Web基类
 */
class App_H5_Web extends Base_App
{
    protected $lgmode;        //页面逻辑模式 -- pub (公开页面,不需登录); pri (私有页面,需要登录)
    protected $smarty;        //smarty 工具对象

    function __construct($lgmode = 'pri', $tmplpath = H5_TEMPLATE_PATH)
    {
        $this->lgmode = $lgmode;
        $this->smarty = new Tool_Smarty($tmplpath);
    }

    protected function checkAuth()
    {
        $testUid = !empty($_REQUEST['_testhls_uid'])? $_REQUEST['_testhls_uid']:
                    (!empty($_COOKIE['_testhls_uid'])? $_COOKIE['_testhls_uid']: 0);
        if (!empty($testUid)) { //测试期间，观察用户数据使用
                $testUserInfo = User_Api::getUserInfoByUid($testUid);
            if (!empty($testUserInfo)) {
                $this->_uid = $testUid;
                $this->_user = $testUserInfo;
                if (!empty($_REQUEST['_testhls_uid'])) {
                    $this->setCookie('_testhls_uid', $testUid, Conf_Base::H5_TOKEN_EXPIRED);
                }
            }
        }

        if (empty($this->_uid)) { //常规登录检查
            $this->checkLogin();
        }

        if (empty($this->_uid)) { //平台授权登录检查
            $this->enterSceneLogin();
        }

        //only-for: test
        //$this->setTestUserLoginInfo();
        //return;

        if (!empty($this->_uid)) { //读取体质
            $userConstitution = Tcm_Api::getConstitution($this->_uid); //@todo t_user表冗余一个字段
            $this->_user['constitution'] = !empty($userConstitution)? $userConstitution['main']: '';

            //当前平台的关注信息
            $this->checkSubScribeForScene();

        } else { //未登陆，清缓存
            self::clearVerifyCookie();
        }
    }

    protected function setCommonPara()
    {
        $this->smarty->assign('_imgHost', H5_IMG_HOST);
        $this->smarty->assign("_wwwHost", H5_HOST);
        $this->smarty->assign("_uid", $this->_uid);
        $this->smarty->assign("_user", $this->_user);
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

    protected function checkLogin()
    {
        $verify = Tool_Input::clean('c', '_m_session', TYPE_STR);
        $user = User_Api::getUserInfoFromVerify($verify, Conf_Base::H5_TOKEN_EXPIRED);

        if (!empty($user)){
            $this->_uid = $user['uid'];
            $this->_user = $user['user_info'];
            return true;
        }

        return false;
    }

    //平台授权登录
    protected function enterSceneLogin()
    {
        //读取平台/场景
        //$authPassport = Access_AuthFactory::getInstance()->entrance();
        $authPassport = Access_Api::authLogin();
        if ($authPassport['errno'] != 0) {
            return;
        }

        if(!empty($authPassport['data']['verify'])){ //已关注； 自动登录
            $this->setCookie('_m_session', $authPassport['data']['verify'], Conf_Base::H5_TOKEN_EXPIRED);
            $this->setCookie('_m_uid', $authPassport['data']['uid'], Conf_Base::H5_TOKEN_EXPIRED);

            $this->_uid = $authPassport['data']['uid'];
            $this->_user = User_Api::getUserInfoByUid($this->_uid);
            $this->_user['is_subscribe'] = $authPassport['data']['is_subscribe'];
        }
    }

    protected function checkSubScribeForScene()
    {
        if (!isset($this->_user['is_subscribe'])) {
            $sceneInfo = Access_Api::subscribeByScene($this->_uid);
            $this->_user['is_subscribe'] = $sceneInfo['is_subscribe'];
        }

    }

    protected function setCookie($field, $value, $expiredTime)
    {
        $expiredTime = !empty($expiredTime) ? (time() + $expiredTime) : 0;
        setcookie($field, $value, $expiredTime, '/', Conf_Base::getH5Host());
    }

    protected function setSessionVerifyCookie($token, $expiredTime = 0)
    {
        $expiredTime = !empty($expiredTime) ? (time() + $expiredTime) : 0;
        setcookie("_m_session", $token, $expiredTime, "/", Conf_Base::getH5Host());
        setcookie('_m_uid', $this->_uid, $expiredTime, '/', Conf_Base::getH5Host());
    }

    protected static function clearVerifyCookie()
    {
        setcookie('_m_session', '', -86400, '/', Conf_Base::getH5Host());
        setcookie('_m_uid', '', -86400, '/', Conf_Base::getH5Host());
        setcookie('_aas', '', -86400, '/', Conf_Base::getH5Host());
        setcookie('_scene_id', '', -86400, '/', Conf_Base::getH5Host());
    }

    protected function delegateTo($path)
    {
        chdir(H5_HTDOCS_PATH . "/" . dirname($path));

        require_once H5_HTDOCS_PATH . "/" . $path;
    }

}
