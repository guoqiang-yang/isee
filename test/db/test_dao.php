<?php
include_once('../../global.php');
class App extends App_Cli
{
    protected function main()
    {
        $this->_test();
    }

    private function _test()
    {
        $dao = new Data_Dao('cms_role');
        $ret = $dao->get(1);
        var_dump($ret);
    }

}

$app = new App();
$app->run();
