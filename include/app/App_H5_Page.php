<?php
require_once ROOT_PATH."/include/vendor/weixin/jssdk/jssdk.php";

/**
 * 管理运营后台 - 普通网页程序基类
 */
class App_H5_Page extends App_H5_Web
{
    protected $headTmpl = 'head/head_common.html';
    protected $tailTmpl = 'tail/tail_common.html';
    protected $title = "";
    protected $page = "";
    protected $hasBottomMenu = true;

    protected $csslist = array();
    protected $headjslist = array('js/jquery.min.js');
    protected $footjslist = array();

    function __construct($lgmode = 'pri', $tmplpath = H5_TEMPLATE_PATH, $cssjs = H5_HOST)
    {
        parent::__construct($lgmode, $tmplpath);
        Tool_CssJs::setCssJsHost($cssjs);
        $this->setCssJs();
    }

    protected function checkAuth()
    {
        parent::checkAuth();

        if ($this->lgmode == 'pri')
        {
            $redirtUri = '';
            if (empty($this->_uid)) { //未登录 or 非微信等平台打开
                $redirtUri = '/user/guide/weixin_subscribe.php';
            } elseif (empty($this->_user['constitution'])) { //体质未测试：体质测试页
                $redirtUri = '/user/intro.php';
            }
            elseif (empty($this->_user['is_subscribe']) && $_SERVER['REQUEST_URI'] != '/user/user_constitution.php') {
                //未关注公众号：提示关注
                $redirtUri = '/user/guide/weixin_subscribe.php';
            }

            if (!empty($redirtUri)) {
                header('Location: '. WEB_PROTOCOL. Conf_Base::getH5Host(). $redirtUri);
                exit;
            }
        }
    }

    protected function setTitle($title)
    {
        $this->title = $title;
    }

    protected function shutDownBottomMenu()
    {
        $this->hasBottomMenu = false;
    }

    protected function setCssJs()
    {
        $this->csslist = array(//'css/index.css',
            'css/hls.css',
        );

        $this->headjslist = array(
            'js/hls.js',
        );

        $this->footjslist = array(
            'js/jquery.min.js',
            'js/base.js',
            'js/tail.js',
        );
    }

    protected function addCss($cssList)
    {
        $this->csslist = array_merge($this->csslist, $cssList);
    }

    protected function addHeadJs($jsList)
    {
        if (is_string($jsList))
        {
            $jsList = array($jsList);
        }
        $this->headjslist = array_merge($this->headjslist, $jsList);
    }

    protected function addFootJs($jsList)
    {
        if (is_string($jsList))
        {
            $jsList = array($jsList);
        }
        $this->footjslist = array_merge($this->footjslist, $jsList);
    }

    protected function removeJs()
    {
        $this->headjslist = array();
        $this->setCssJs();
    }

    protected function setHeadTmpl($tmpl)
    {
        $this->headTmpl = $tmpl;
    }

    protected function setTailTmpl($tmpl)
    {
        $this->tailTmpl = $tmpl;
    }

    protected function outputHttp()
    {
        if (!headers_sent())
        {
            header("Content-Type: text/html; charset=" . SYS_CHARSET);
            if ($this->_uid > 0)
            {
                header("Cache-Control: no-cache; private");
            } else
            {
                header("Cache-Control: no-cache");
            }
            header("Pragma: no-cache");
        }
    }

    protected function outputHead()
    {
        $this->title = empty($this->title) ? '黄老食' : $this->title;

        $this->smarty->assign('title', $this->title);
        $this->smarty->assign('cssHtml', Tool_CssJs::getCssHtml($this->csslist));
        $this->smarty->assign('jsHtml', Tool_CssJs::getJsHtml($this->headjslist));

        $this->smarty->display($this->headTmpl);
    }

    protected function outputTail()
    {
        $jsHtml = Tool_CssJs::getJsHtml($this->footjslist, true);
        $this->smarty->assign('jsHtml', $jsHtml);

        $jsEnv = array('wwwHost' => H5_HOST);
        $this->smarty->assign('jsEnv', $jsEnv);

        //微信分享
        $account = Access_Config::get(Conf_Base::REG_SOURCE_WEIXIN);
        $jssdk = new JSSDK($account['app_id'], $account['app_key']);
        $url = '';
        $signPackage = $jssdk->GetSignPackage($url);
        $this->smarty->assign('sign_package',json_encode($signPackage));
        $this->smarty->assign('cur_url',$url);

        $pathInfo = pathinfo($_SERVER['REQUEST_URI']);
        $this->smarty->assign('foot_nav_flag', $pathInfo['filename']);
        $this->smarty->assign('has_bottom_menu', $this->hasBottomMenu);
        $this->smarty->display($this->tailTmpl);

    }

    protected function setCommonPara()
    {
        parent::setCommonPara();
    }

    protected function showError($ex)
    {
        echo "<!-- \n";
        var_export($ex);
        echo "-->\n";

        $GLOBALS['t_exception'] = $ex;
        $this->delegateTo("common" . DS . "500.php");
        Tool_Log::debug('@app_page', "code:" . $ex->getCode() . "\nerror:" . $ex->getMessage() . "\n" . var_export($ex->getTrace(), true));
        exit;
    }

    protected function getCurrentPage()
    {
        $res = parse_url($_SERVER['REQUEST_URI']);
        $module = trim(dirname($res['path']), "\/");
        $page = basename($res['path'], '.php');

        if (!empty($this->page))
        {
            $page = $this->page;
        }

        return array($module, $page);
    }

}
