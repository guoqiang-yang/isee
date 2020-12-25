<?php

include_once('../global.php');

class App extends App_Admin_Page
{
    protected function getPara()
    {
        echo File_Oss_Api::genOssFileNameForShi('4.jpg');
        echo "\n";
        echo File_Oss_Api::genOssFileNameForShi('5.jpg');

        exit;
    }

    protected function main()
    {
        //获取体质形容词
        //$this->getConstitutionWords();

        //获取菜谱的Tags
        $this->getRecipeTags();


        exit;
    }

    protected function outputBody()
    {
        //$this->smarty->display('dashboard.html');
    }

    private function getConstitutionWords()
    {
        $oDao = new Data_Dao('t_material');

        $list = $oDao->setFields(array('constitution_good', 'constitution_bad'))->getListWhere('1=1', false);

        $words = array();
        foreach($list as $item) {
            $this->parseConstitution($item['constitution_good'], $words);
            $this->parseConstitution($item['constitution_bad'], $words);
        }

        print_r($words);
    }

    private function getRecipeTags()
    {
        $oDao = new Data_Dao('t_recipe');
        $list = $oDao->setFields(array('tags'))->getListWhere('1=1', false);

        $tags = array();
        foreach($list as $strTag){
            $arrTags = (explode(',', $strTag['tags']));
            foreach ($arrTags as $tagName) {
                $tags[$tagName] = empty($tags[$tagName])? 1 : $tags[$tagName]+1;
            }
        }

        echo implode("', '", array_keys($tags));exit;
        print_r($tags);
    }

    private function parseConstitution($constitution, &$words)
    {
        if (empty($constitution)) {
            return;
        }

        $constitutionList = explode(',', $constitution);

        foreach($constitutionList as $item) {
            list($word, $weight) = explode(':', $item);
            $words[$word] += 1;
        }
    }


}

$app = new App('pub');
$app->run();