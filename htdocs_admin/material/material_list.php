<?php

include_once ('../../global.php');

class App extends App_Admin_Page
{
    private $pageNum = 20;
    private $start;
    private $total;
    private $materialList;
    private $queryParams;

    protected function getPara()
    {
        $this->start = Tool_Input::clean('r', 'start', 'int');

        $this->queryParams = array(
            'id' => Tool_Input::clean('r', 'id', 'int'),
            'category' => Tool_Input::clean('r',  'category', 'str'),
        );
    }

    protected function main()
    {
        $this->materialList = Material_Api::getListByCond($this->queryParams, $this->start, $this->pageNum);
        $this->total = Material_Api::getTotalByCond($this->queryParams);

        $this->format($this->materialList);

        $this->addCss(array(
            'css/plugins/rwd_table.min.css',
        ));
        $this->addFootJs(array(
            'js/plugins/rwd_table.min.js',
            'js/common/modals.js',
        ));
    }

    protected function outputBody()
    {
        $pageHtml = Str_Html::getSimplePage2($this->start, $this->pageNum, $this->total, '/material/material_list.php');

        $this->smarty->assign('sub_title', '食材列表');
        $this->smarty->assign('page_html', $pageHtml);
        $this->smarty->assign('query', $this->queryParams);
        $this->smarty->assign('base_config', $this->getBaseConfig());
        $this->smarty->assign('material_list', $this->materialList);
        $this->smarty->display('material/material_list.html');
    }

    private function format(&$materialList)
    {
        foreach ($materialList as &$item) {
            $item['material_detail_url'] = 'ajax/material_detail.php?id=' . $item['id'];
            $item['constitution_good'] = Conf_Constitution::parseConstitution($item['constitution_good']);
            $item['constitution_bad'] = Conf_Constitution::parseConstitution($item['constitution_bad']);
        }
    }

    private function getBaseConfig()
    {
        return array(
            'category' => Conf_Material_Old::getTopCategoryInfos(),
        );
    }
}

$app = new App('pri');
$app->run();