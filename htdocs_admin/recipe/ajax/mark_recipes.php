<?php

include_once ('../../../global.php');

class App extends App_Admin_Ajax
{
    private $rids;
    private $status;
    private $difficulty;
    private $popularity_rank;
    private $category;

    protected function getPara()
    {
        $this->rids = Tool_Input::clean('r', 'rids', 'str');
        $this->status = Tool_Input::clean('r', 'status', 'int');
        $this->difficulty = Tool_Input::clean('r', 'difficulty', 'int');
        $this->popularity_rank = Tool_Input::clean('r', 'popularity_rank', 'int');
        $this->category = Tool_Input::clean('r', 'category', 'str');

        $this->rids = explode(",", $this->rids);
        $this->rids = array_values(array_filter($this->rids));
    }

    protected function checkPara()
    {
        if (empty($this->rids))
        {
            throw new Exception('No rids');
        }
    }

    protected function main()
    {
        $update = array();
        if (!empty($this->status)) {
            $update['status'] = $this->status;
        }
        if (!empty($this->difficulty)) {
            $update['difficulty'] = $this->difficulty;
        }
        if (!empty($this->popularity_rank)) {
            $update['popularity_rank'] = $this->popularity_rank;
        }
        if (!empty($this->category)) {
            $update['category'] = $this->category;
        }
        $where = array('id' => $this->rids);
        $mdao = new Data_Dao('t_recipe');
        $mdao->updateWhere($where, $update);
    }

    protected function outputBody()
    {
        $response = new Response_Ajax();
        $response->setContent(array('rest' => 0));
        $response->send();
    }
}

$app = new App('pri');
$app->run();
