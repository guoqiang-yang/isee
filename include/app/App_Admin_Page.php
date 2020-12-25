<?php

/**
 * 管理运营后台 - 普通网页程序基类
 */
class App_Admin_Page extends App_Admin_Web
{
    protected $headTmpl = 'head/head_page.html';
    protected $tailTmpl = 'tail/tail_page.html';
    protected $title = "";
    protected $page = "";

    protected $csslist = array();
    protected $headjslist = array();
    protected $footjslist = array();

    function __construct($lgmode = 'pri', $tmplpath = ADMIN_TEMPLATE_PATH, $cssjs = ADMIN_HOST)
    {
        parent::__construct($lgmode, $tmplpath);
        Tool_CssJs::setCssJsHost($cssjs);
        $this->setCssJs();
    }

    protected function checkAuth($permission = '')
    {
        parent::checkAuth();
        if ($this->lgmode == 'pri' && empty($this->_uid)) {
            header('Location: http://' . Conf_Base::getAdminHost() . '/user/login.php');
            exit;
        }
    }

    protected function setTitle($title)
    {
        $this->title = $title;
    }

    protected function setCssJs()
    {
        $this->csslist = array(//'css/index.css',
            'css/plugins/custombox.min.css',

            'css/bootstrap.min.css',
            'css/icons.css',
            'css/metismenu.min.css',
            'css/style.css',
        );

        $this->headjslist = array(
            'js/modernizr.min.js',
            'js/jquery.min.js',
        );

        $this->footjslist = array(
            'js/popper.min.js',
            'js/bootstrap.min.js',
            'js/metisMenu.min.js',
            'js/waves.js',
            'js/jquery.slimscroll.js',

            'js/plugins/custombox.min.js',
            'js/plugins/legacy.min.js',

            'js/jquery.core.js',
            'js/jquery.app.js',

            'js/common/base.js',
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

    protected function getSubNavigation()
    {
        return array(
            'sub_title' => 'input_sub_title',
        );
    }

    protected function outputHead()
    {
        $this->title = empty($this->title) ? 'hls管理系统' : $this->title;


        list($module, $page) = $this->getCurrentPage();

        $this->smarty->assign('curPage', $page);
        $this->smarty->assign('curModule', $module);
        $this->smarty->assign('modules', Conf_Admin_Page::getMODULES($this->_uid, $this->_user));
        $this->smarty->assign('title', $this->title);
        $this->smarty->assign('cssHtml', Tool_CssJs::getCssHtml($this->csslist));
        $this->smarty->assign('jsHtml', Tool_CssJs::getJsHtml($this->headjslist));
        $this->smarty->assign('uid', $this->_uid);
        $this->smarty->assign('user', $this->_user);
        $this->smarty->assign('sub_navigation', $this->getSubNavigation());

        $this->smarty->display($this->headTmpl);
    }

    protected function outputTail()
    {
        $jsHtml = Tool_CssJs::getJsHtml($this->footjslist, true);
        $this->smarty->assign('jsHtml', $jsHtml);
        $this->smarty->assign('footerJsHtml', Tool_CssJs::getJsHtml($this->footjslist));

        $jsEnv = array("wwwHost" => ADMIN_HOST);
        $this->smarty->assign("jsEnv", $jsEnv);
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
