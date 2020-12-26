<?php

include_once('../global.php');

class App extends App_Admin_Page
{
    protected function getPara()
    {
    }

    protected function main()
    {
        echo "I am here";
    }

    protected function outputBody()
    {
        //$this->smarty->display('dashboard.html');
    }

}

$app = new App('pub');
$app->run();