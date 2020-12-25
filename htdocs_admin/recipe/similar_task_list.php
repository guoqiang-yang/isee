<?php

include_once ('../../global.php');

class App extends App_Admin_Page
{
    private $step;
    private $start;

    private $pageNum = 20;
    private $total;
    private $taskList;

    protected function getPara()
    {
        $this->step = Tool_Input::clean('r', 'step', 'int');
        $this->start = Tool_Input::clean('r', 'start', 'int');
    }

    protected function checkPara()
    {
    }

    protected function main()
    {
        $this->taskList = $this->_getTasks($this->step, $this->start, $this->pageNum);
        $this->total = $this->_getTotal($this->step);

        $this->format($this->taskList);

        $this->addCss(array(
            'css/plugins/rwd_table.min.css',
        ));
        $this->addFootJs(array(
            'js/plugins/rwd_table.min.js',
            'js/common/modals.js',
        ));
    }

    private function _getTasks($step, $start, $pageNum)
    {
        $dao = new Data_Dao('t_similar_recipes');
        $where = array('step'=>$step);
        $tasks = $dao->limit($start, $pageNum)->getListWhere($where);
        return $tasks;
    }

    private function _getTotal($step)
    {
        $dao = new Data_Dao('t_similar_recipes');
        $where = array('step'=>$step);
        $total = $dao->getTotal($where);
        return $total;
    }

    protected function outputBody()
    {
        $pageHtml = Str_Html::getSimplePage2($this->start, $this->pageNum, $this->total, '/recipe/similar_task_list.php?step='.$this->step);

        $this->smarty->assign('page_html', $pageHtml);
        $this->smarty->assign('sub_title', '相似菜谱任务');
        $this->smarty->assign('task_list', $this->taskList);
        $this->smarty->display('recipe/similar_task_list.html');
    }

    private function format(&$taskList)
    {
        foreach($taskList as &$item) {
        }
    }
}

$app = new App('pri');
$app->run();