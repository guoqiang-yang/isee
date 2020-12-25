<?php

include_once ('../../global.php');

class App extends App_Admin_Page
{
    private $query;
    private $pageNum = 20;
    private $start;
    private $total;
    private $recipeList;

    public function getPara()
    {
        $this->start = Tool_Input::clean('r', 'start', 'int');
        $this->query = array(
            'id' => Tool_Input::clean('r', 'id', 'int'),
            'category' => Tool_Input::clean('r', 'category', 'str'),
            'constitution' => Tool_Input::clean('r', 'constitution', 'str'),
            'status' => Tool_Input::clean('r', 'status', 'int'),
        );
    }

    protected function main()
    {
        $this->recipeList = Recipe_Api::getListByCond($this->query, $this->start, $this->pageNum);
        $this->total = Recipe_Api::getTotalByCond($this->query);

        $this->format($this->recipeList);

        $this->addCss(array(
            'css/plugins/rwd_table.min.css',
        ));
        $this->addFootJs(array(
            'js/plugins/rwd_table.min.js',
            'js/hls/recipe.js',
        ));
    }

    protected function outputBody()
    {
        $pageHtml = Str_Html::getSimplePage2($this->start, $this->pageNum, $this->total, '/recipe/recipe_list.php');

        $this->smarty->assign('sub_title', '菜谱列表');
        $this->smarty->assign('query', $this->query);
        $this->smarty->assign('base_config', $this->getBaseConfig());
        $this->smarty->assign('page_html', $pageHtml);
        $this->smarty->assign('recipe_list', $this->recipeList);
        $this->smarty->display('recipe/recipe_list.html');
    }

    private function getBaseConfig()
    {
        return array(
            'category' => Conf_Recipe_Category::getAllCategories(),
            'constitution' => Conf_Constitution::getConstitutions(),
        );
    }

    private function format(&$materialList)
    {
        foreach($materialList as &$item) {
            $item['tags'] = explode(',', $item['tags']);
            $item['constitution'] = $this->formatConstitution($item['constitution']);
        }
    }

    private function formatConstitution($constitution)
    {
        $constitutionList = Conf_Constitution::parseConstitution($constitution);

        $grouped = array();
        foreach($constitutionList as $item) {
            if ($item['weight'] < 0) {
                $grouped['bad'][] = $item;
            } else {
                $grouped['good'][] = $item;
            }
        }

        return $grouped;
    }

}

$app = new App('pri');
$app->run();