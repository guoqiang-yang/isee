<?php
include_once('../../global.php');

class App extends App_Admin_Page
{
    private $mobile;
    private $loginErrmsg = '';

    protected function getPara()
    {
        $this->mobile = Tool_Input::clean('g', 'mobile', 'str');
        $this->loginErrmsg = Tool_Input::clean('g', 'login_errmsg', 'str');
    }

    protected function main()
    {
        $this->setHeadTmpl('head/head_login.html');
        $this->setTailTmpl('tail/tail_login.html');
    }

    protected function outputBody()
    {
        $this->smarty->assign('mobile', $this->mobile);
        $this->smarty->assign('login_errmsg', $this->loginErrmsg);
        $this->smarty->display('user/login.html');
    }
}

$app = new App('pub');
$app->run();
