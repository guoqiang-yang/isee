<?php
include_once ('../../../global.php');

class App extends App_Admin_Ajax
{
    private $ids;
    private $step;

    protected function getPara()
    {
        $this->ids = Tool_Input::clean('r', 'ids', 'str');
        $this->step = Tool_Input::clean('r', 'step', 'int');

        $this->ids = explode(",", $this->ids);
        $this->ids = array_values(array_filter($this->ids));
    }

    protected function checkPara()
    {
        if (empty($this->ids))
        {
            throw new Exception('No ids');
        }
        if (empty($this->step))
        {
            throw new Exception('No step');
        }
    }

    protected function main()
    {
        $update = array('step' => $this->step);
        $where = array('id' => $this->ids);
        $mdao = new Data_Dao('t_similar_recipes');
        $mdao->updateWhere($where, $update);
    }

    protected function outputBody()
    {
        $response = new Response_Ajax();
        $response->setContent(array('ret' => 0));
        $response->send();
    }
}

$app = new App('pri');
$app->run();
