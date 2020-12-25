<?php
include_once('../../global.php');

class App extends App_Cli
{
    protected function main()
    {
        $this->_delRecipesCache();
    }

    private function _delRecipesCache()
    {
        $mc = Data_Memcache::getInstance();
        $filelds = array('id','name','category','tags');
        $cacheKey = substr(md5(implode('', $filelds)),0,16);
        $key = Conf_Cache::ALL_RECIPES_CACHE_NAME.$cacheKey;
        $mc->delete($key);
        $jsonStr = $mc->get($key);
        var_dump($jsonStr);

        $key = Conf_Cache::ALL_MATERIALS_CACHE_NAME;
        $mc = Data_Memcache::getInstance();
        $mc->delete($key);
        $jsonStr = $mc->get($key);
        var_dump($jsonStr);

        $key = Conf_Cache::ALL_RECIPE_MATERIAL_CACHE_NAME;
        $mc = Data_Memcache::getInstance();
        $c = $mc->get($key);
        echo "{$key} has 0/1:" . (!empty($c)?1:0) ."\n";
        $mc->delete($key);
        $jsonStr = $mc->get($key);
        var_dump($jsonStr);
    }
}

$app = new App();
$app->run();
