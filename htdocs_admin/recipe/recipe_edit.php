<?php

include_once ("../../global.php");

class App extends App_Admin_Page
{
    private $recipeId;
    private $recipeInfo;

    protected function getPara()
    {
        $this->recipeId = Tool_Input::clean('r', 'id', 'int');

    }

    protected function main()
    {
        $this->recipeInfo = Recipe_Api::getRecipeInfo($this->recipeId);
        $this->formatConsitution();

        $this->addCss(array(
            'css/plugins/select2.min.css',
        ));
        $this->addFootJs(array(
            'js/plugins/select2.min.js',
            'js/plugins/bootstrap_select.min.js',
            //'js/plugins/bootstrap_filestyle.min.js',
            'js/plugins/jquery.bootstrap_touchspin.min.js',
            'js/plugins/bootstrap_maxlength.js',
            'js/plugins/jquery.form_advanced.init.js',
            'js/hls/recipe.js',
            'js/common/fileUploader.js',
            'js/common/uploadPic.js',
        ));
    }

    protected function outputBody()
    {
        $this->smarty->assign('sub_title', '菜谱编辑');
        $this->smarty->assign('base_config', $this->getConfig());
        //$this->smarty->assign('all_recipe_tags', $this->genRecipeTagsVals());
        $this->smarty->assign('recipe_detail', $this->recipeInfo);
        $this->smarty->display('recipe/recipe_edit.html');

    }

    private function formatConsitution()
    {
        $excludeList = array_diff(
            Conf_Constitution::getConstitutions(),
            array_keys($this->recipeInfo['constitution'])
        );

        foreach($excludeList as $itemName) {
            $this->recipeInfo['constitution'][$itemName] = 0;
        }
    }

    private function getConfig()
    {
        return array(
            'category' => Conf_Recipe_Category::getAllCategories(),
        );
    }

    private function genRecipeTagsVals()
    {
        $allTags = array(
            '家常菜', '荤菜', '小吃', '快手菜', '热菜', '主食', '饺子', '面食', '早餐', '饼', '包子',
            '盒子', '日韩', '汤', '韩国菜', '炸', '烘焙', '饼干', '披萨', '西餐', '面包', '面条',
            '凉菜', '粥', '法国菜', '便当', '火锅', '素菜', '东南亚', '印度菜', '意大利', '烧烤', '凉面',
            '炒饭', '日本菜', '汁', '盖浇饭', '海鲜', '锅贴', '派', '三明治', '炒面', '蛋糕', '羹', '煲仔饭',
            '寿司', '糕点', '意大利菜', '春卷', '戚风蛋糕', '曲奇', '酱', '馒头', '糊', '点心', '饮', '馄饨',
            '吐司', '蛋挞', '挞', '月饼', '罐头', '初秋', '芝士蛋糕', '慕斯', '膏', '蛋糕卷', '汤圆', '冰沙',
            '冰品', '冰激凌', '泡芙', '马芬', '甜点', '杯子蛋糕', '羮', '焖面', '泰国菜', '越南菜', '煎饼', '拌面',
            '汉堡', '焗饭', '元宵', '卷饼', '河粉', '饭团', '发糕', '窝头', '焖饭', '炒饼', '海绵蛋糕', '西班牙菜',
            '春饼', '翻糖蛋糕', '提拉米苏', '沙拉', '玉米饼', '奶油蛋糕', '乳酪蛋糕',
        );

        $recipeTags = array();
        $selectedTags = explode(',', $this->recipeInfo['tags']);
        foreach($allTags as $item) {
            $recipeTags[] = array(
                'name' => $item,
                'selected' => in_array($item, $selectedTags),
            );
        }

        return $recipeTags;
    }
}

$app = new App('pri');
$app->run();