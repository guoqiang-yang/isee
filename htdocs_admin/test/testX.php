<?php


include_once ('../../global.php');

class App extends App_Admin_Page
{
    private $val;
    protected function getPara()
    {
        $this->val = Tool_Input::clean('r', 'val', TYPE_STR);
    }

    protected function main()
    {

    }

    protected function outputBody()
    {
        $this->smarty->assign('val', $this->val);
        $this->smarty->display('test/testX.html');
    }
}

$app = new App('pub');
$app->run();